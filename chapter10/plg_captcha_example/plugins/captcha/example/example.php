<?php
/**
 * Example CAPTCHA Plugin for Joomla!
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Class PlgCaptchaExample
 *
 * @since  September 2014
 */
class PlgCaptchaExample extends JPlugin
{
	/**
	 * Load the language file on instantiation (for Joomla! 3.X only)
	 *
	 * @var    boolean
	 * @since  3.3
	 */
	protected $autoloadLanguage = true;

	/**
	 * Listing of question types
	 *
	 * @var    array
	 */
	protected $question_types = array(
		'vowels' => 'PLG_CAPTCHA_EXAMPLE_FIELD_QUESTION_VOWELS',
		'consonants' => 'PLG_CAPTCHA_EXAMPLE_FIELD_QUESTION_CONSONANTS',
		'capitals' => 'PLG_CAPTCHA_EXAMPLE_FIELD_QUESTION_CAPITALS',
	);

	/**
	 * Event method loaded on CAPTCHA initialization
	 *
	 * @param   mixed  $id  HTML identifier
	 *
	 * @return null
	 */
	public function onInit($id)
	{
		$this->question_word = $this->generateRandomString();
		$this->question_type = array_rand($this->question_types, 1);

		$session = JFactory::getSession();
		$session->set('captcha.example.word', $this->question_word);
		$session->set('captcha.example.type', $this->question_type);
	}

	/**
	 * Event method run when the CAPTCHA needs to be displayed
	 *
	 * @param   string  $name   HTML name
	 * @param   mixed   $id     HTML identifier
	 * @param   string  $class  HTML class
	 *
	 * @return string
	 */
	public function onDisplay($name, $id, $class)
	{
		$html = JText::_($this->question_types[$this->question_type]);
		$html .= '<pre>' . $this->question_word . '</pre>';
		$html .= '<hr/>';
		$html .= JText::_('PLG_CAPTCHA_EXAMPLE_FIELD_ANSWER') . ': ';
		$html .= '<input type="text" name="' . $name . '" id="' . $id . '" class="' . $class . '" />';

		return $html;
	}

	/**
	 * Event method run to check the CAPTCHA response given by the user
	 *
	 * @param   string  $answer  Text inputted by the user
	 *
	 * @return bool
	 */
	public function onCheckAnswer($answer)
	{
		$answer = (int) trim($answer);

		if (!$answer > 0)
		{
			$this->_subject->setError(JText::_('PLG_CAPTCHA_EXAMPLE_ERROR_NO_RESPONSE'));

			return false;
		}

		$session = JFactory::getSession();
		$question_type = $session->get('captcha.example.type');
		$question_word = $session->get('captcha.example.word');

		if ($this->matchAnswer($answer, $question_type, $question_word) == false)
		{
			$this->_subject->setError(JText::sprintf('PLG_CAPTCHA_EXAMPLE_ERROR_INCORRECT', $answer));

			return false;
		}

		return true;
	}

	/**
	 * Method to match the answer with a certain question
	 *
	 * @param   string  $answer         String of the answer
	 * @param   string  $question_type  Question type
	 * @param   string  $question_word  Question word
	 *
	 * @return bool
	 */
	protected function matchAnswer($answer, $question_type, $question_word)
	{
		$validAnswer = 0;

		switch ($question_type)
		{
			case 'capitals':
				preg_match_all('/[ABCDEFGHIJKLMNOPQRSTUVWXYZ]/', $question_word, $matches);
				$validAnswer = count($matches[0]);
				break;

			case 'vowels':
				preg_match_all('/[aouie]/i', $question_word, $matches);
				$validAnswer = count($matches[0]);
				break;

			case 'consonants':
				preg_match_all('/[bcdfghjklmnpqrstvwxyz]/i', $question_word, $matches);
				$validAnswer = count($matches[0]);
				break;
		}

		if ($answer == $validAnswer)
		{
			return true;
		}

		return false;
	}

	/**
	 * Method to generate a random string
	 *
	 * @return string
	 */
	protected function generateRandomString()
	{
		$characters = array();

		$ranges = array(
			'abcdefghijklmnopqrstuvwxyz',
			'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'0123456789',
			'aAeEiIoOuU',
			'bBcCdDfFgGhHjJkKlLmMnNpPqQrRsStTvVwWxXyYzZ',
		);

		foreach ($ranges as $range)
		{
			$length = rand(2, 4);
			$rangeCharacters = $this->generateRandomStringFromRange($length, $range);
			$characters = array_merge($characters, $rangeCharacters);
		}

		shuffle($characters);

		return implode('', $characters);
	}

	/**
	 * Method to generate a random string from a certain range of characters
	 *
	 * @param   int     $length  Length of the required string
	 * @param   string  $range   Characters containing the range
	 *
	 * @return array
	 */
	protected function generateRandomStringFromRange($length, $range)
	{
		$base = strlen($range);
		$randomChars = array();

		$random = JCrypt::genRandomBytes($length + 1);
		$shift = ord($random[0]);

		for ($i = 1; $i <= $length; ++$i)
		{
			$randomChars[] = $range[($shift + ord($random[$i])) % $base];
			$shift += ord($random[$i]);
		}

		return $randomChars;
	}
}

<?php
/**
 * Example CAPTCHA Plugin for Joomla!
 *
 * @author Jisse Reitsma (jisse@yireo.com)
 * @copyright Copyright 2014 Jisse Reitsma
 * @license GNU Public License version 3 or later
 * @link http://www.yireo.com/books/
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCaptchaExample extends JPlugin
{
	protected $autoloadLanguage = true;

    protected $question_types = array(
        'vowels' => 'PLG_CAPTCHA_EXAMPLE_FIELD_QUESTION_VOWELS',
        'consonants' => 'PLG_CAPTCHA_EXAMPLE_FIELD_QUESTION_CONSONANTS',
        'capitals' => 'PLG_CAPTCHA_EXAMPLE_FIELD_QUESTION_CAPITALS',
    );

	public function onInit($id)
    {
        $this->question_word = $this->generateRandomString();
        $this->question_type = array_rand($this->question_types, 1);

        $session = JFactory::getSession();
        $session->set('captcha.example.word', $this->question_word);
        $session->set('captcha.example.type', $this->question_type);
    }

	public function onDisplay($name, $id, $class)
	{
        $html = JText::_($this->question_types[$this->question_type]);
        $html .= '<pre>'.$this->question_word.'</pre>';
        $html .= '<hr/>';
        $html .= JText::_('PLG_CAPTCHA_EXAMPLE_FIELD_ANSWER').': ';
        $html .= '<input type="text" name="'.$name.'" id="'.$id.'" class="'.$class.'" />';
        return $html;
    }

	public function onCheckAnswer($answer)
	{
		$answer = (int)trim($answer);
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

    protected function matchAnswer($answer, $question_type, $question_word)
    {
        $validAnswer = 0;
        switch($question_type) 
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

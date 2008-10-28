<?php
/**
 * mod_forms.php - Generate forms and repopulate after submit
 * $Id$
 * Copyright 2008 mbscholt at aquariusoft.org
 *
 * Qik is the legal property of its developer, Michiel Scholten
 * [mbscholtNOSPAM@aquariusoft.org]
 * Please refer to the COPYRIGHT file distributed with this source distribution.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define(FORMITEM_TEXT, 'text');
define(FORMITEM_EMAIL, 'email');
define(FORMITEM_PASSWORD, 'password');
define(FORMITEM_CHECKBOX, 'checkbox');
define(FORMITEM_RADIO, 'radio');

/*
 * Form item, an input of the kind:
 * - text / email
 * - password
 * - checkbox
 */
class FormItem
{
	protected $name, $tag, $value, $kind, $required;
	protected $errorMsg;
	
	public function __construct($name, $tag, $value, $kind, $required)
	{
		$this->name = $name;
		$this->tag = $tag;
		$this->value = $value;
		$this->kind = $kind;
		$this->required = $required;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}

	public function isRequired()
	{
		return $this->required;
	}

	public function getTag()
	{
		return $this->tag;
	}

	public function getKind()
	{
		return $this->kind;
	}

	public function isEmail()
	{
		return (FORMITEM_EMAIL == $this->kind);
	}

	public function setError($message)
	{
		$this->errorMsg = $message;
	}
	
	public function getError()
	{
		return $this->errorMsg;
	}

	public function toText()
	{
		$theInput = '';
		if ('text' == $this->kind || 'password' == $this->kind || 'email' == $this->kind)
		{
			$thisKind = $this->kind;
			if ('email' == $thisKind) { $thisKind = 'text'; }
			$theInput = $this->name . ': ' . $this->value;
		} else if ('checkbox' == $this->kind || 'radio' == $this->kind)
		{
			$checked = 'no';
			if (true === $this->value)
			{
				$checked = 'yes';
			}
			$theInput = $this->name . ': ' . $checked;
		}
		return $theInput;
	}

	public function __toString()
	{
		$reqString = '';
		$theInput = '';
		if (true === $this->required)
		{
			$reqString = ' *';
		}
		if ('text' == $this->kind || 'password' == $this->kind || 'email' == $this->kind)
		{
			$thisKind = $this->kind;
			if ('email' == $thisKind) { $thisKind = 'text'; }
			$theInput = '<input type="' . $thisKind . '" name="' . $this->tag . '" value="' . $this->value . '" size="40" maxlength="100" />&nbsp;<span class="heading">' . $this->name . $reqString . '</span>';
		} else if ('checkbox' == $this->kind || 'radio' == $this->kind)
		{
			$checked = '';
			if (true === $this->value)
			{
				$checked = ' checked';
			}
			$theInput = '<input type="' . $this->kind . '" name="' . $this->tag . '"' . $checked . ' />&nbsp;' . $this->name . $reqString;
		}
		return '<p>' . $theInput . '</p>';
	}
}


/*
 * Block of FormItem's, with a heading
 */
class FormBlock
{
	protected $formItems = array();
	protected $title;
	
	public function __construct($title='', $items=array())
	{
		$this->title = $title;
		$this->formItems = $items;
	}
	
	public function addItem($item)
	{
		$this->formItems[count($this->formItems)] = $item;
	}

	/* Looks for FormItem with $tag */
	public function getItem($tag)
	{
		for ($i = 0; $i < count($this->formItems); $i++)
		{
			if ($this->formItems[$i]->getTag() == $tag)
			{
				return $this->formItems[$i];
			}
		}
		return null;
	}

	public function readFromPost()
	{
		$allGood = true;
		for ($i = 0; $i < count($this->formItems); $i++)
		{
			$newValue = null;
			if (FORMITEM_CHECKBOX == $this->formItems[$i]->getKind() || FORMITEM_RADIO == $this->formItems[$i]->getKind())
			{
				if (!isset($_REQUEST[$this->formItems[$i]->getTag()]))
				{
					$newValue = false;
				} else
				{
					$newValue = true;
				}
			} else
			{
				$newValue = getRequestParam($this->formItems[$i]->getTag(), $this->formItems[$i]->getValue());
			}
			if ('' === $newValue && $this->formItems[$i]->isRequired())
			{
				$this->formItems[$i]->setError('form_isrequired_field');
				$allGood = false;
			} else if ($this->formItems[$i]->isEmail() && '' != trim($newValue) && !isValidEmail($newValue))
			{
				$this->formItems[$i]->setError('form_need_valid_email');
				$allGood = false;
			}
			$this->formItems[$i]->setValue( $newValue );
		}
		return $allGood;
	}

	public function toText()
	{
		$result = '';
		if ('' != $this->title)
		{
			$result = '== ' . html_entity_decode($this->title) . " ======\n";
		}
		for ($i = 0; $i < count($this->formItems); $i++)
		{
			$result .= $this->formItems[$i]->toText() . "\n";
		}
		return $result;
	}

	public function __toString()
	{
		$result = '';
		if ('' != $this->title)
		{
			$result = '<h3>' . $this->title . "</h3>\n";
		}
		for ($i = 0; $i < count($this->formItems); $i++)
		{
			$thisError = $this->formItems[$i]->getError();
			if ('' != $thisError)
			{
				$result .= '<p><em>@@@dict=' . $thisError . "@@@</em></p>\n";
			}
			$result .= $this->formItems[$i] . "\n";
		}
		return $result;
	}
}


/*
 * The form, consisting of FormBlock's, a tag identifying the form and the form action
 */
class Form
{
	protected $tag;						/* Identifier of the form */
	protected $formBlocks = array();	/* The blocks of items the form consists of */
	protected $action;					/* The action the form posts to */

	public function __construct($tag, $action, $blocks = array(), $submit = 'submit')
	{
		$this->tag = $tag;
		$this->formBlocks = $blocks;
		$this->action = $action;
		$this->submitText = $submit;
	}

	public function addBlock($block)
	{
		$this->formItems[count($this->formBlocks)] = $item;
	}

	public function toText()
	{
		$result = '';
		for ($i = 0; $i < count($this->formBlocks); $i++)
		{
			$result .= $this->formBlocks[$i]->toText() . "\n";
		}
		return $result;
	}
	
	public function __toString()
	{
		$result = '<form id="' . $this->tag . '" method="post" action="' . $this->action . "\">\n";
		for ($i = 0; $i < count($this->formBlocks); $i++)
		{
			$result .= $this->formBlocks[$i];
		}
		$result .= '<p><input id="' . $this->tag . 'savebtn" name="savebtn" value="' . $this->submitText . '" type="submit" /></p>';
		return $result;
	}
}
?>

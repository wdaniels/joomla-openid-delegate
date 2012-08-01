<?php
/**
* OpenID Delegation Plugin for Joomla!
*
* This is a system plugin which adds relationship links to the document's
* HTML head element for delegating OpenID authentication to a given service.
*
* The main purpose of this to allow somebody using Joomla! for their personal
* website to use the site URL as their OpenID. Not only does this implicitly
* "claim" your site under your OpenID, it has the added advantage that you then
* have the freedom to change the OpenID service provider without needing to
* changing your OpenID URL(s).
*
* @author     Will Daniels <mail@willdaniels.co.uk>
* @version    0.1 (proto)
* @copyright  Copyright (C) 2009 Will Daniels. All rights reserved.
* @license    GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.event.plugin');

/**
* System - OpenID Delegation
*/
class plgSystemOpenID_Delegate extends JPlugin
{
	/**
	* Constructor
	*
	* For php4 compatability we must not use the __constructor as a constructor
	* for plugins because func_get_args ( void ) returns a copy of all passed
	* arguments NOT references.  This causes problems with cross-referencing
	* necessary for the observer design pattern.
	*/
	function plgSystemOpenID_Delegate(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	* System event onAfterInitialise; invoked automatically by Joomla!
	*
	* If we're not in the admin area, the delegation variables have been set
	* and we're serving a HTML document, then we add the openid delegation 
	* links to the document's head element here.
	*
	* @todo Check whether this is the best event to do this in!
	* @todo Find out if we can check for admin without global variable
	*/
	function onAfterInitialise()
	{
		global $mainframe;

		if(!$mainframe->isAdmin())
		{
			$server = $this->params->get('openid_link_server', '');
			$delegate = $this->params->get('openid_link_delegate', '');
	
			if(strlen($server) + strlen($delegate))
			{
				$doc = JFactory::getDocument();
				if(get_class($doc) == 'JDocumentHTML')
				{
					$doc->addHeadLink($server, 'openid.server');
					$doc->addHeadLink($delegate, 'openid.delegate');
				}
			}
		}
		
		return true;
	}
}

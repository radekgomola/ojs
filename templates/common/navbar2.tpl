{**
 * templates/common/navbar.tpl
 *
 * Copyright (c) 2013 Simon Fraser University Library
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Navigation Bar
 *
 *}
<div id="navbar">
	<ul class="menu">
                
		{translate|assign:"help" key="navigation.home"}
		<li id="home"><a class="button-home {if $requestedPage=="index"}home-selected{/if} " href="{url page="index"}">{translate key="navigation.home"}</a></li>
    {if $currentJournal && $currentJournal->getSetting('publishingMode') != $smarty.const.PUBLISHING_MODE_NONE}
                    <li id="about"><a class="button-about {if $requestedPage=="about"}about-selected{/if}" href="{url page="about"}">{translate key="navigation.about.journal"}</a>
                        <ul class="submenu-about">
                            <li><a class="submenu-contact" href="{url page="about" op="contact"}">{translate key="about.contact"}</a></li>
                            <li><a class="submenu-editorialTeam" href="{url page="about" op="editorialTeam"}">{translate key="about.editorialTeam"}</a></li>
                            <li><a class="submenu-policies" href="{url page="about" op="editorialPolicies"}">{translate key="about.policies"}</a></li>              
                            {*odkaz pouze pro Mujlt a Revue *}
                            <li><a class="submenu-others" href="{url page="about" op="editorialPolicies"}">{translate key="about.other"}</a></li>
                            {*po sem *}
                        </ul>
                    </li>
                  
    {else}
      <li id="about"><a class="button-about {if $requestedPage=="about"}about-selected{/if}" href="{url page="about"}">{translate key="navigation.about"}</a></li>
    {/if}
    

    {if $currentJournal && $currentJournal->getSetting('publishingMode') != $smarty.const.PUBLISHING_MODE_NONE}
      {if $pageTitle=="archive.archives"}
        {assign var=archive_test value='true'}
      {elseif $requestedPage=="issue"}
        {assign var=archive_test value='false'}
      {/if}
			<li id="current"><a class="button-current {if $archive_test=="false"}current-selected{/if}" href="{url page="issue" op="current"}">{translate key="navigation.current"}</a></li>
			<li id="archives"><a class="button-archive {if $archive_test=="true" }archive-selected{/if}" href="{url page="issue" op="archive"}">{translate key="navigation.archives"}</a></li>
		{/if}

		{if $enableAnnouncements}
			<li id="announcements_li"><a class="button-announce {if $requestedPage=="announcement"}announce-selected{/if}" href="{url page="announcement"}">{translate key="announcement.announcements"}</a></li>
		{/if}{* enableAnnouncements *}
    
		{if $isUserLoggedIn}
			<li id="userHome"><a class="button-user {if $requestedPage=="user"}user-selected{/if}" href="{url page="user"}">{translate key="navigation.userHome"}</a></li>
		{else}
			<li id="login"><a class="button-login {if $requestedPage=="login"}login-selected{/if}" href="{url page="login"}">{translate key="navigation.login"}</a></li>
			{if !$hideRegisterLink}
				<li id="register"><a class="button-register {if $requestedPage=="user"}register-selected{/if}" href="{url page="user" op="register"}">{translate key="navigation.register"}</a></li>
			{/if}
		{/if}{* $isUserLoggedIn *}

		{if $siteCategoriesEnabled}
			<li id="categories"><a class="button-categories {if $requestedPage=="search"}categories-selected{/if}"href="{url journal="index" page="search" op="categories"}">{translate key="navigation.categories"}</a></li>
		{/if}{* $categoriesEnabled *}

		{if !$currentJournal || $currentJournal->getSetting('publishingMode') != $smarty.const.PUBLISHING_MODE_NONE}
			<li id="search"><a class="button-search {if $requestedPage=="search"}search-selected{/if}"  href="{url page="search"}">{translate key="navigation.search"}</a></li>
		{/if}


		{call_hook name="Templates::Common::Header::Navbar::CurrentJournal"}

		{foreach from=$navMenuItems item=navItem key=navItemKey}
			{if $navItem.url != '' && $navItem.name != ''}
				<li class="navItem" id="navItem-{$navItemKey|escape}"><a class="navItem-{$navItemKey|escape} {if $requestedPage=="navItem-{$navItemKey|escape}"}navItem-{$navItemKey|escape}-selected{/if}" href="{if $navItem.isAbsolute}{$navItem.url|escape}{else}{$baseUrl}{$navItem.url|escape}{/if}">{if $navItem.isLiteral}{$navItem.name|escape}{else}{translate key=$navItem.name}{/if}</a></li>
			{/if}
		{/foreach}
	</ul>
</div>
    


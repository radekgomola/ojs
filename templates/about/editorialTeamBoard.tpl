{**
 * templates/about/editorialTeamBoard.tpl
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * About the Journal index.
 *
 *}
{strip}
{assign var="pageTitle" value="about.editorialTeam"}
{include file="common/header.tpl"}
{/strip}

{call_hook name="Templates::About::EditorialTeam::Information"}
{foreach from=$groups item=group}
<div id="group">
	<h4>{$group->getLocalizedTitle()}</h4>
	{assign var=groupId value=$group->getId()}
	{assign var=members value=$teamInfo[$groupId]}

        {if $group->getOpacnyTvarJmena()}
            {assign var=tvarJmena value=true}
        {else}
            {assign var=tvarJmena value=false}
        {/if}
        {if $group->getAllowMedailon()}
            {assign var=allowMedailon value=true}
        {else}
            {assign var=allowMedailon value=false}
        {/if}
	<ol class="editorialTeam">
		{foreach from=$members item=member}
			{assign var=user value=$member->getUser()}
			<div class="member">
                            <li><span class="narrow">&#187; </span>
                                {if $allowMedailon[$groupId]}
                                    {if !$group->getFullProfile()}<a href="javascript:openRTWindow('{url op="editorialTeamBio" path=$user->getId()}')">{else}<a href="{url op="editorialTeamBioFullProfile" path=$user->getId()}">{/if}{$user->getFullName($tvarJmena)|escape}</a>{else}<span class="editTeamName">{$user->getFullName($tvarJmena)|escape}</span>{/if}{if $user->getLocalizedAffiliation()}, {$user->getLocalizedAffiliation()|escape}{/if}{if $user->getCountry()}{assign var=countryCode value=$user->getCountry()}{assign var=country value=$countries.$countryCode}, {$country|escape}{/if}
                                <div class="editTeamEmailUrl">
                                    {if $publishEmailList[$groupId]  && !$user->getAllowPublishingEmail()}
                                        <span class="editTeamEmail">{translate key="editorialTeam.email"}: </span> 
                                        {assign_mail_address var=address address=$user->getEmail()|escape}
                                        {assign_mailto var=mailtoAddress address=$user->getEmail()|escape}
                                        <a href="{$mailtoAddress}" target="_new">{$address}</a>
                                        <br />
                                    {/if}
                                    {if $publishUrlList[$groupId] && $user->getUrl()}                                
                                        <span class="editTeamEmail">{translate key="editorialTeam.url"}: </span> 
                                        <a href="{$user->getUrl()|escape:"quotes"}" target="_new">{$user->getUrl()|escape}</a><br/>
                                    {/if} 
                                </div>
                            </li>
                        </div>
		{/foreach}{* $members *}
	</ol>
</div>
{/foreach}{* $groups *}

{include file="common/footer.tpl"}


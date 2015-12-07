{**
 * templates/about/displayMembership.tpl
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Display group membership information.
 *
 *}
{strip}
{assign var="pageTitle" value="about.people"}
{include file="common/header.tpl"}
{/strip}

<div id="displayMembership">
<h4>{$group->getLocalizedTitle()}</h4>
{assign var=groupId value=$group->getId()}
{assign var=j value=0}
{if $group->getOpacnyTvarJmena()}
    {assign var=tvarJmena value=true}
{else}
    {assign var=tvarJmena value=false}
{/if}
<table style="listing" width="100%">
<tr valign="top">
    <td style="width:50%">
        <ol class="editorialTeam">
            {assign var=membershipCount value=$memberships|@count}

            {foreach from=$memberships item=member}
                {assign var=user value=$member->getUser()}
                
                <div class="member">
                    <li>&#187; 
                        {if $allowMedailon}
                            {if !$group->getFullProfile()}<a href="javascript:openRTWindow('{url op="editorialTeamBio" path=$user->getId()}')">{else}<a href="{url op="editorialTeamBioFullProfile" path=$user->getId()}">{/if}{$user->getFullName($tvarJmena)|escape}</a>{else}<span class="editTeamName">{$user->getFullName($tvarJmena)|escape}</span>{/if}{if $user->getLocalizedAffiliation()}, {$user->getLocalizedAffiliation()|escape}{/if}{if $user->getCountry()}{assign var=countryCode value=$user->getCountry()}{assign var=country value=$countries.$countryCode}, {$country|escape}{/if} 
                        <div class="editTeamEmailUrl">
                            {if $publishEmailList  && !$user->getAllowPublishingEmail()}
                                <span class="editTeamEmail">{translate key="editorialTeam.email"}: </span> 
                                {assign_mail_address var=address address=$user->getEmail()|escape}
                                {assign_mailto var=mailtoAddress address=$user->getEmail()|escape}
                                <a href="{$mailtoAddress}" target="_new">{$address}</a>
                                <br />
                            {/if}
                            {if $publishUrlList && $user->getUrl()}                                
                                <span class="editTeamEmail">{translate key="editorialTeam.url"}: </span> 
                                <a href="{$user->getUrl()|escape:"quotes"}" target="_new">{$user->getUrl()|escape}</a><br/>
                            {/if} 
                        </div>
                    </li>
                </div>
                {if $j==$membershipCount/2|ceil && $j>25} </ol></td><td><ol class="editorialTeam">{/if}
                {assign var=j value=$j+1}
            {/foreach}
        </ol>
    </td>
</tr>
</table>
</div>

{include file="common/footer.tpl"}


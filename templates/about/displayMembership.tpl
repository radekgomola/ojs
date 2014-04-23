{**
 * templates/about/displayMembership.tpl
 *
 * Copyright (c) 2013 Simon Fraser University Library
 * Copyright (c) 2003-2013 John Willinsky
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
<table style="listing" width="100%">
<tr valign="top">
<td>
<ol class="editorialTeam">
{assign var=membershipCount value=$memberships|@count}

{foreach from=$memberships item=member}
	{assign var=user value=$member->getUser()}
	<div class="member"><li>&#187; <a href="javascript:openRTWindow('{url op="editorialTeamBio" path=$user->getId()}')">{$user->getFullName(true)|escape}</a>{if $user->getLocalizedAffiliation()}, {$user->getLocalizedAffiliation()|escape}{/if}{if $user->getCountry()}{assign var=countryCode value=$user->getCountry()}{assign var=country value=$countries.$countryCode}, {$country|escape}{/if} </li></div>
  {if $j==$membershipCount/2|ceil && $j>25} </ol></td><td><ol class="editorialTeam">{/if}
  {assign var=j value=$j+1}
{/foreach}
</ol>
</td>
</tr>
</table>
</div>

{include file="common/footer.tpl"}


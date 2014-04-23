{**
 * plugins/blocks/social/block.tpl
 *
 * Copyright (c) 2013 Simon Fraser University Library
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site sidebar menu -- social networks links.
 *
 *}
 

{if !empty($socialFacebook) || !empty($socialTwitter) || !empty($socialGplus)}
<div class="block" id="socialNetworks">
<span class="blockTitle">{translate key="plugins.block.socialNetworks"}</span>
{if !empty($socialFacebook)}
  <a href="{$socialFacebook}" target="_blank"><img src="https://journals.muni.cz/images/facebook_small.png" name="Facebook.com" /></a>
{/if}
{if !empty($socialTwitter)}  
  <a href="{$socialTwitter}" target="_blank"><img src="https://journals.muni.cz/images/twitter_small.png" name="Twitter.com" /></a>
{/if}
{if !empty($socialGplus)}
  <a href="{$socialGplus}" target="_blank"><img src="https://journals.muni.cz/images/gplus_small.png" name="Plus.google.com" /></a>
{/if}
</div>
{/if}

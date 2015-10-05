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

{if !empty($socFacebook) || !empty($socTwitter) || !empty($socGplus)}
<div class="block" id="socialNetworks">
<span class="blockTitle">{translate key="plugins.block.socialNetworks"}</span>
{if !empty($socFacebook)}
  <a href="{$socFacebook}" target="_blank"><img src="https://journals.muni.cz/images/facebook_small.png" alt="Facebook.com" /></a>
{/if}
{if !empty($socTwitter)}  
  <a href="{$socTwitter}" target="_blank"><img src="https://journals.muni.cz/images/twitter_small.png" alt="Twitter.com" /></a>
{/if}
{if !empty($socGplus)}
  <a href="{$socGplus}" target="_blank"><img src="https://journals.muni.cz/images/gplus_small.png" alt="Plus.google.com" /></a>
{/if}
</div>
{/if}

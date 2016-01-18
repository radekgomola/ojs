{**
 * plugins/blocks/user/block.tpl
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site sidebar menu -- user tools.
 *
 *}
<div class="block" id="sidebarUserSmall">
	{if $isUserLoggedIn}
                <div id="sidebarUserSmallBlock">                    
                    <div class="profilProfil">
                        {translate key="plugins.block.userSmall.loggedInAs"}
                    </div>
                    <div class="profileLogin">{$loggedInUsername|escape}</div>
                    <div class="profilImg">
                        <a href="#" onclick="toggle_visibility('userSmallChoose')"><span class="profilImgLink">{translate key="plugins.block.userSmall.advanced"}</span></a>
                    </div>
                </div>
                <div id="userSmallChoose">                    
                    <span class="userDashboard"><a href="{url page="user"}">{translate key="plugins.block.userSmall.dashboard"}</a><br /></span>        
                    {if $hasOtherJournals}
                                <span class="userOtherJournals"><a href="{url journal="index" page="user"}">{translate key="plugins.block.userSmall.myJournals"}</a><br /></span>
                            {/if}
                            <span class="userProfile"><a href="{url page="user" op="profile"}">{translate key="plugins.block.userSmall.myProfile"}</a><br /></span>
                            <span class="userSignOut"><a href="{url page="login" op="signOut"}">{translate key="plugins.block.userSmall.logout"}</a><br /></span>
                            {if $userSession->getSessionVar('signedInAs')}
                            <span class="userSignOutAs"><a href="{url page="login" op="signOutAsUser"}">{translate key="plugins.block.userSmall.signOutAsUser"}</a><br /></span>
                            {/if}
                    </ul>
                </div>
	{else}
            <div id="profilPrihlaseni">   
			<a href="{$userSmallBlockLoginUrl}">{translate key="user.login"}</a>		
            </div>
	{/if}
</div>

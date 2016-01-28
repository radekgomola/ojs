{**
 * plugins/blocks/searchSmall/block.tpl
 * search sidebar
 *
 *}
{*{if !$currentJournal || $currentJournal->getSetting('publishingMode') != $smarty.const.PUBLISHING_MODE_NONE}*}
<div class="block" id="searchSmallBlock">
        {if $requestedPage == "search"}
            {if isset($query) && ($query|escape) != ""}
                {assign var=searchValue value=$query}
            {elseif isset($authors) && ($authors|escape) != ""}
                {assign var=searchValue value=$authors}
            {elseif isset($title) && ($title|escape) != ""}
                {assign var=searchValue value=$title}
            {elseif isset($abstract) && $abstract != ""}
                {assign var=searchValue value=$abstract}
            {else}
                {assign var=searchValue value=""}
            {/if}
        {/if}
	<form id="smallSearchForm" action="{url page="search" op="search"}">
                <div id="searchSmallSubmit">
                    <input type="submit" value="{translate key="common.search"}" class="button_lupa" />
                </div>
                <div id="searchSmallMainPart">
                    {capture assign="filterInput"}{call_hook name="Templates::Search::SearchResults::FilterInput" filterName="simpleQuery" filterValue="" size=15}{/capture}
                    {if empty($filterInput)}
                            <input type="text" id="simpleQuery" name="simpleQuery" size="15" maxlength="255" value="{$searchValue|escape}" class="textField" />
                    {else}
                            {$filterInput}
                    {/if}
                </div>
                <div class="searchSmallRozsirene">
                    <a href="#" onclick="toggle_visibility('searchSmallChoose')">{translate key="plugins.block.searchSmall.rozsirene"}</a>
                </div>
                <div id="searchSmallChoose">
                    
                    <select id="searchField" name="searchField" size="1" class="selectMenu">
                            {html_options_translate options=$articleSearchByOptions}
                    </select>
                    <div class="searchProchazet">                        
                        <span class="byIssue"><a href="{url page="issue" op="archive"}">{translate key="navigation.browseByIssue"}</a><br /></span>
                        <span class="byAuthors"><a href="{url page="search" op="authors"}" >{translate key="navigation.browseByAuthor"}</a><br /></span>
                        <span class="byTitles"><a href="{url page="search" op="titles"}" >{translate key="navigation.browseByTitle"}</a><br /></span>
                        {if $hasOtherJournals}
                                <span class="otherJournals"><a href="{url journal="index"}">{translate key="navigation.otherJournals"}</a><br /></span>
                                {if $siteCategoriesEnabled}<span class="byCategories"><a href="{url journal="index" page="search" op="categories"}">{translate key="navigation.categories"}</a><br /></span>
                                {/if}
                        {/if}
                    </div>
                </div>
	</form>
</div>
{*{/if}*}

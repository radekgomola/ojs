{**
 * templates/content.tpl
 *
 * Copyright (c) 2014-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Display Static Page content
 *}
{assign var="pageTitleTranslated" value=$title}
{include file="common/header.tpl"}

<p>{$content}</p>

{include file="common/footer.tpl"}

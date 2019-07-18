<div style="float: left; margin-right:10px;">
<form method="post" id="submitArchives" action="{url page="editor" op="submissions" path=submissionsArchives}">
	<input type="hidden" name="sort" value="id"/>
	<input type="hidden" name="sortDirection" value="ASC"/>
	<input type="hidden" name="searchField" value="1">
	<input type="hidden" name="searchMatch" value="is">
	<input type="hidden" name="search" value="{$prijmeni}" />
	<input type="hidden" name="dateToHour" value="23" />
	<input type="hidden" name="dateToMinute" value="59" />
	<input type="hidden" name="dateToSecond" value="59" />
	<br/>
	<input type="submit" value="{translate key="cyberpsychology.search.author.archive"}" class="button" />
</form>
</div>
<div style="float: left; margin-right:10px;">
<form method="post" id="submitArchives" action="{url page="editor" op="submissions" path=submissionsInReview}">
	<input type="hidden" name="sort" value="id"/>
	<input type="hidden" name="sortDirection" value="ASC"/>
	<input type="hidden" name="searchField" value="1">
	<input type="hidden" name="searchMatch" value="is">
	<input type="hidden" name="search" value="{$prijmeni}" />
	<input type="hidden" name="dateToHour" value="23" />
	<input type="hidden" name="dateToMinute" value="59" />
	<input type="hidden" name="dateToSecond" value="59" />
	<br/>
	<input type="submit" value="{translate key="cyberpsychology.search.author.inReview"}" class="button" />
</form>
</div>
<div style="float: left; margin-right:10px;">
<form method="post" id="submitArchives" action="{url page="editor" op="submissions" path=submissionsArchives}">
	<input type="hidden" name="sort" value="id"/>
	<input type="hidden" name="sortDirection" value="ASC"/>
	<input type="hidden" name="searchField" value="4">
	<input type="hidden" name="searchMatch" value="is">
	<input type="hidden" name="search" value="{$prijmeni}" />
	<input type="hidden" name="dateToHour" value="23" />
	<input type="hidden" name="dateToMinute" value="59" />
	<input type="hidden" name="dateToSecond" value="59" />
	<br/>
	<input type="submit" value="{translate key="cyberpsychology.search.reviewer.archive"}" class="button" />
</form>
</div>
<div style="float: left; margin-right:10px;">
<form method="post" id="submitArchives" action="{url page="editor" op="submissions" path=submissionsInReview}">
	<input type="hidden" name="sort" value="id"/>
	<input type="hidden" name="sortDirection" value="ASC"/>
	<input type="hidden" name="searchField" value="4">
	<input type="hidden" name="searchMatch" value="is">
	<input type="hidden" name="search" value="{$prijmeni}" />
	<input type="hidden" name="dateToHour" value="23" />
	<input type="hidden" name="dateToMinute" value="59" />
	<input type="hidden" name="dateToSecond" value="59" />
	<br/>
	<input type="submit" value="{translate key="cyberpsychology.search.reviewer.inReview"}" class="button" />
</form>
</div>
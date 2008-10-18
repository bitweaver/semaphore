{strip}
{form}
	<input type="hidden" name="page" value="{$page}" />

	{legend legend="Semaphore Settings"}
		<div class="row">
			{formlabel label="Warning time" for="semaphore_limit"}
			{forminput}
				{html_options name="semaphore_limit" options=$limit values=$limit selected=$gBitSystem->getConfig('semaphore_limit') id=semaphore_limit} {tr}minute(s){/tr}
				{formhelp note="Duration an edit warning appears after a user has started editing a given content. We recommend about 2-5 minutes."}
			{/forminput}
		</div>
	{/legend}

	<div class="row submit">
		<input type="submit" name="change_prefs" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
{/strip}

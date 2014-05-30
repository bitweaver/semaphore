{strip}
{form}
	<input type="hidden" name="page" value="{$page}" />

	{legend legend="Semaphore Settings"}
		<div class="form-group">
			{formlabel label="Warning time" for="semaphore_limit"}
			{forminput}
				{html_options name="semaphore_limit" options=$limit values=$limit selected=$gBitSystem->getConfig('semaphore_limit') id=semaphore_limit} {tr}minute(s){/tr}
				{formhelp note="Duration an edit warning appears after a user has started editing a given content. We recommend about 5 or 10 minutes."}
			{/forminput}
		</div>
	{/legend}

	<div class="form-group submit">
		<input type="submit" class="btn btn-default" name="change_prefs" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
{/strip}

<div class='cc-dialog hidden'>
    <?= "<h4>" . $settings['banner_title'] . "</h4>" ?>
    <?= "<p>" . $settings['banner_text'] . "</p>" ?>
    <?= "<a href='" . $settings['cookiepolicy_path'] . "'>Cookie policy →</a>" ?>
    <div class='cc-checkboxes'>
        <h5>Set cookie preferences</h5>
        {exp:consent:form form_id='consent-form'}
        {consents}
        <p><b>{consent_title}</b></p>
        <div>
            <label><input type='radio' name='{consent_short_name}' value='y' {if consent_granted}checked{/if}> Accept </label>
            <label><input type='radio' name='{consent_short_name}' value='n' {if ! consent_granted}checked{/if}> Decline </label>
        </div>
        {/consents}
        {/exp:consent:form}
    </div>
    <div class='cc-buttons'>
        <a class='cc-button-save'>Save preferences</a>
        <a class='cc-button-preferences'>Set preferences</a>
        <a class='cc-button-accept'>Accept all</a>
    </div>
</div>
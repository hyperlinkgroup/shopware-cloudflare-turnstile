import template from './sw-settings-captcha-select-v2.html.twig';
import deDE from './../../snippet/de-DE.json';
import enGB from './../../snippet/en-GB.json';

Shopware.Component.override('sw-settings-captcha-select-v2', {
    template,

    inject: ['cloudflareTurnstileApiCredentialsService'],

    mixins: [
        'notification',
    ],

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB,
    },

    data() {
        return {
            turnstileCredentialsValid: false,
            turnstileIsValidatingCredentials: false,
        };
    },

    computed: {
        turnstileWidgetModeOptions() {
            return [
                {
                    label: this.$tc('sw-settings-basic-information.captcha.label.cloudflareTurnstileWidgetModeManaged'),
                    value: 'managed',
                },
                {
                    label: this.$tc('sw-settings-basic-information.captcha.label.cloudflareTurnstileWidgetModeNonInteractive'),
                    value: 'non-interactive',
                },
                {
                    label: this.$tc('sw-settings-basic-information.captcha.label.cloudflareTurnstileWidgetModeInvisible'),
                    value: 'invisible',
                },
            ];
        },

        turnstileThemeOptions() {
            return [
                {
                    label: this.$tc('sw-settings-basic-information.captcha.label.cloudflareTurnstileThemeAuto'),
                    value: 'auto',
                },
                {
                    label: this.$tc('sw-settings-basic-information.captcha.label.cloudflareTurnstileThemeLight'),
                    value: 'light',
                },
                {
                    label: this.$tc('sw-settings-basic-information.captcha.label.cloudflareTurnstileThemeDark'),
                    value: 'dark',
                },
            ];
        },
    },

    methods: {
        validateTurnstileApiCredentials() {
            this.turnstileIsValidatingCredentials = true;

            this.cloudflareTurnstileApiCredentialsService.validateApiCredentials(
                this.currentValue.cloudflareTurnstile.config.siteKey,
                this.currentValue.cloudflareTurnstile.config.secretKey,
            ).then(this.onTurnstileValidationResponse).catch(this.onTurnstileValidationError);
        },

        onTurnstileValidationResponse(response) {
            this.turnstileCredentialsValid = response.credentialsValid;

            if (this.turnstileCredentialsValid) {
                this.createNotificationSuccess({
                    message: this.$tc('sw-settings-basic-information.captcha.notification.turnstileValidCredentials'),
                });
            } else {
                this.createNotificationError({
                    message: this.$tc('sw-settings-basic-information.captcha.notification.turnstileInvalidCredentials'),
                });
            }

            this.turnstileIsValidatingCredentials = false;
        },

        onTurnstileValidationError() {
            this.createNotificationError({
                message: this.$tc('sw-settings-basic-information.captcha.notification.turnstileErrorInCredentialsValidation'),
            });

            this.turnstileCredentialsValid = false;
            this.turnstileIsValidatingCredentials = false;
        },

        turnstileHasKeys() {
            return this.currentValue.cloudflareTurnstile
                && this.currentValue.cloudflareTurnstile.config
                && this.currentValue.cloudflareTurnstile.config.siteKey.length > 0
                && this.currentValue.cloudflareTurnstile.config.secretKey.length > 0;
        },
    },
});


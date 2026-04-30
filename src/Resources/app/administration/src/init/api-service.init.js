import CloudflareTurnstileApiCredentialsService from '../core/service/api/cloudflare-turnstile-api-credentials.service';

const { Application } = Shopware;

const initContainer = Application.getContainer('init');

Application.addServiceProvider(
    'cloudflareTurnstileApiCredentialsService',
    (container) => new CloudflareTurnstileApiCredentialsService(
        initContainer.httpClient,
        container.loginService,
    ),
);


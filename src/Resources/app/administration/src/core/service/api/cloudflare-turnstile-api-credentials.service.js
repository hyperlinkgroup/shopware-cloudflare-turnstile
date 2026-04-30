const ApiService = Shopware.Classes.ApiService;

export default class CloudflareTurnstileApiCredentialsService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'cloudflare-turnstile') {
        super(httpClient, loginService, apiEndpoint);
    }

    /**
     * @param {string} siteKey
     * @param {string} secretKey
     * @returns {Promise}
     */
    validateApiCredentials(siteKey, secretKey) {
        const headers = this.getBasicHeaders();

        return this.httpClient.get(
            `_action/${this.getApiBasePath()}/validate-api-credentials`,
            {
                params: { siteKey, secretKey },
                headers: headers,
            },
        ).then((response) => {
            return ApiService.handleResponse(response);
        });
    }
}


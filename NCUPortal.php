<?php

namespace OAuth\OAuth2\Service;

use OAuth\OAuth2\Token\StdOAuth2Token;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Uri\UriInterface;

class NCUPortal extends AbstractService
{
    const SCOPE_READONLY = '';
    const SCOPE_IDENTIFIER = 'identifier';
    const SCOPE_CHINESE_NAME = 'chinese-name';
    const SCOPE_ENGLISH_NAME = 'english-name';
    const SCOPE_GENDER = 'gender';
    const SCOPE_BIRTHDAY = 'birthday';
    const SCOPE_PERSONAL_ID = 'personal-id';
    const SCOPE_STUDENT_ID = 'student-id';
    const SCOPE_ACADEMY_RECORDS = 'academy-records';
    const SCOPE_FACULTY_RECORDS = 'faculty-records';
    const SCOPE_EMAIL = 'email';
    const SCOPE_MOBILE_PHONE = 'mobile-phone';

    public function __construct(
        CredentialsInterface $credentials,
        ClientInterface $httpClient,
        TokenStorageInterface $storage,
        $scopes = array(),
        UriInterface $baseApiUri = null
    ) {
        parent::__construct($credentials, $httpClient, $storage, $scopes, $baseApiUri);

        if (null === $baseApiUri) {
            $this->baseApiUri = new Uri('https://portal.ncu.edu.tw/');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationEndpoint()
    {
        return new Uri('https://portal.ncu.edu.tw/oauth2/authorization');
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenEndpoint()
    {
        return new Uri('https://portal.ncu.edu.tw/oauth2/token');
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthorizationMethod()
    {
	return static::AUTHORIZATION_METHOD_HEADER_BEARER;
    }

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        $data = json_decode($responseBody, true);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data['error'])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data['error'] . '"');
        }

        $token = new StdOAuth2Token();
        $token->setAccessToken($data['access_token']);
        // Github tokens evidently never expire...
        $token->setEndOfLife(StdOAuth2Token::EOL_NEVER_EXPIRES);
        unset($data['access_token']);

        $token->setExtraParams($data);

        return $token;
    }

    /**
     * Used to configure response type -- we want JSON from github, default is query string format
     *
     * @return array
     */
    protected function getExtraOAuthHeaders()
    {
        return array(
		'Accept' => 'application/json',
		'Authorization' => 'Basic ' .
			base64_encode($this->credentials->getConsumerId() . ':' . $this->credentials->getConsumerSecret())
	);
    }

    /**
     * @return array
     */
    protected function getExtraApiHeaders()
    {
        return array('Accept' => 'application/json');
    }

    /**
     * {@inheritdoc}
     */
    protected function getScopesDelimiter()
    {
        return ' ';
    }
}

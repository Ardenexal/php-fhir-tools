<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Assertion Response Types
 * URL: http://hl7.org/fhir/ValueSet/assert-response-code-types
 * Version: 5.0.0
 * Description: The type of response code to use for assertion.
 */
enum AssertionResponseTypes: string
{
	/** Continue */
	case continue = 'continue';

	/** Switching Protocols */
	case switchingprotocols = 'switchingProtocols';

	/** OK */
	case ok = 'okay';

	/** Created */
	case created = 'created';

	/** Accepted */
	case accepted = 'accepted';

	/** Non-Authoritative Information */
	case nonauthoritativeinformation = 'nonAuthoritativeInformation';

	/** No Content */
	case nocontent = 'noContent';

	/** Reset Content */
	case resetcontent = 'resetContent';

	/** Partial Content */
	case partialcontent = 'partialContent';

	/** Multiple Choices */
	case multiplechoices = 'multipleChoices';

	/** Moved Permanently */
	case movedpermanently = 'movedPermanently';

	/** Found */
	case found = 'found';

	/** See Other */
	case seeother = 'seeOther';

	/** Not Modified */
	case notmodified = 'notModified';

	/** Use Proxy */
	case useproxy = 'useProxy';

	/** Temporary Redirect */
	case temporaryredirect = 'temporaryRedirect';

	/** Permanent Redirect */
	case permanentredirect = 'permanentRedirect';

	/** Bad Request */
	case badrequest = 'badRequest';

	/** Unauthorized */
	case unauthorized = 'unauthorized';

	/** Payment Required */
	case paymentrequired = 'paymentRequired';

	/** Forbidden */
	case forbidden = 'forbidden';

	/** Not Found */
	case notfound = 'notFound';

	/** Method Not Allowed */
	case methodnotallowed = 'methodNotAllowed';

	/** Not Acceptable */
	case notacceptable = 'notAcceptable';

	/** Proxy Authentication Required */
	case proxyauthenticationrequired = 'proxyAuthenticationRequired';

	/** Request Timeout */
	case requesttimeout = 'requestTimeout';

	/** Conflict */
	case conflict = 'conflict';

	/** Gone */
	case gone = 'gone';

	/** Length Required */
	case lengthrequired = 'lengthRequired';

	/** Precondition Failed */
	case preconditionfailed = 'preconditionFailed';

	/** Content Too Large */
	case contenttoolarge = 'contentTooLarge';

	/** URI Too Long */
	case uritoolong = 'uriTooLong';

	/** Unsupported Media Type */
	case unsupportedmediatype = 'unsupportedMediaType';

	/** Range Not Satisfiable */
	case rangenotsatisfiable = 'rangeNotSatisfiable';

	/** Expectation Failed */
	case expectationfailed = 'expectationFailed';

	/** Misdirected Request */
	case misdirectedrequest = 'misdirectedRequest';

	/** Unprocessable Content */
	case unprocessablecontent = 'unprocessableContent';

	/** Upgrade Required */
	case upgraderequired = 'upgradeRequired';

	/** Internal Server Error */
	case internalservererror = 'internalServerError';

	/** Not Implemented */
	case notimplemented = 'notImplemented';

	/** Bad Gateway */
	case badgateway = 'badGateway';

	/** Service Unavailable */
	case serviceunavailable = 'serviceUnavailable';

	/** Gateway Timeout */
	case gatewaytimeout = 'gatewayTimeout';

	/** HTTP Version Not Supported */
	case httpversionnotsupported = 'httpVersionNotSupported';
}

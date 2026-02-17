<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: repositoryType
 * URL: http://hl7.org/fhir/ValueSet/repository-type
 * Version: 4.0.1
 * Description: Type for access of external URI.
 */
enum RepositoryType: string
{
    /** Click and see */
    case clickandsee = 'directlink';

    /** The URL is the RESTful or other kind of API that can access to the result. */
    case theurlistherestfulorotherkindofapithatcanaccesstotheresult = 'openapi';

    /** Result cannot be access unless an account is logged in */
    case resultcannotbeaccessunlessanaccountisloggedin = 'login';

    /** Result need to be fetched with API and need LOGIN( or cookies are required when visiting the link of resource) */
    case resultneedtobefetchedwithapiandneedloginorcookiesarerequiredwhenvisitingthelinkofresource = 'oauth';

    /** Some other complicated or particular way to get resource from URL. */
    case someothercomplicatedorparticularwaytogetresourcefromurl = 'other';
}

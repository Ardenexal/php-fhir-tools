<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Link Relation Types
 * URL: http://hl7.org/fhir/ValueSet/iana-link-relations
 * Version: 5.0.0
 * Description: Link Relation Types defined at https://www.iana.org/assignments/link-relations/link-relations.xhtml#link-relations-1
 */
enum FHIRLinkRelationTypes: string
{
    /** Refers to a resource that is the subject of the link's context. */
    case referst_oa_resourcethatisthesubjectofthelinkscontext = 'about';

    /** Asserts that the link target provides an access control description for the link context. */
    case assertsthatthelinktargetprovidesanaccesscontroldescriptionforthelinkcontext = 'acl';

    /** Refers to a substitute for this context */
    case referst_oa_substituteforthiscontext = 'alternate';

    /** Used to reference alternative content that uses the AMP profile of the HTML format. */
    case usedtoreferencealternativecontentthatusestheampprofileofthehtmlformat = 'amphtml';

    /** Refers to an appendix. */
    case referstoanappendix = 'appendix';

    /** Refers to an icon for the context. Synonym for icon. */
    case referstoaniconforthecontextsynonymforicon = 'apple-touch-icon';

    /** Refers to a launch screen for the context. */
    case referst_oa_launchscreenforthecontext = 'apple-touch-startup-image';

    /**
     * Refers to a collection of records, documents, or other
     *       materials of historical interest.
     */
    case referst_oa_collectionofrecordsdocumentsorothermaterialsofhistoricalinterest = 'archives';

    /** Refers to the context's author. */
    case referstothecontex_ts_author = 'author';

    /**
     * Identifies the entity that blocks access to a resource
     *       following receipt of a legal demand.
     */
    case identifiestheentitythatblocksaccesst_oa_resourcefollowingreceiptofalegaldemand = 'blocked-by';

    /** Gives a permanent link to use for bookmarking purposes. */
    case give_sa_permanentlinktouseforbookmarkingpurposes = 'bookmark';

    /** Designates the preferred version of a resource (the IRI and its contents). */
    case designatesthepreferredversiono_fa_resourcetheirianditscontents = 'canonical';

    /** Refers to a chapter in a collection of resources. */
    case referst_oa_chapterinacollectionofresources = 'chapter';

    /** Indicates that the link target is preferred over the link context for the purpose of permanent citation. */
    case indicatesthatthelinktargetispreferredoverthelinkcontextforthepurposeofpermanentcitation = 'cite-as';

    /** The target IRI points to a resource which represents the collection resource for the context IRI. */
    case thetargetiripointst_oa_resourcewhichrepresentsthecollectionresourceforthecontextiri = 'collection';

    /** Refers to a table of contents. */
    case referst_oa_tableofcontents = 'contents';

    /**
     * The document linked to was later converted to the
     *       document that contains this link relation.  For example, an RFC can
     *       have a link to the Internet-Draft that became the RFC; in that case,
     *       the link relation would be "convertedFrom".
     */
    case thedocumentlinkedtowaslaterconvertedtothedocumentthatcontainsthislinkrelationforexampleanrfccanhav_ea_linktotheinternetdraftthatbecametherfcinthatcasethelinkrelationwouldbeconvertedfrom = 'convertedFrom';

    /**
     * Refers to a copyright statement that applies to the
     *     link's context.
     */
    case referst_oa_copyrightstatementthatappliestothelinkscontext = 'copyright';

    /** The target IRI points to a resource where a submission form can be obtained. */
    case thetargetiripointst_oa_resourcewhereasubmissionformcanbeobtained = 'create-form';

    /**
     * Refers to a resource containing the most recent
     *       item(s) in a collection of resources.
     */
    case referst_oa_resourcecontainingthemostrecentitemsinacollectionofresources = 'current';

    /**
     * Refers to a resource providing information about the
     *       link's context.
     */
    case referst_oa_resourceprovidinginformationaboutthelinkscontext = 'describedby';

    /**
     * The relationship A 'describes' B asserts that
     *       resource A provides a description of resource B. There are no
     *       constraints on the format or representation of either A or B,
     *       neither are there any further constraints on either resource.
     */
    case therelationshi_pa_describesbassertsthatresourceaprovidesadescriptionofresourcebtherearenoconstraintsontheformatorrepresentationofeitheraorbneitherarethereanyfurtherconstraintsoneitherresource = 'describes';

    /**
     * Refers to a list of patent disclosures made with respect to
     *       material for which 'disclosure' relation is specified.
     */
    case referst_oa_listofpatentdisclosuresmadewithrespecttomaterialforwhichdisclosurerelationisspecified = 'disclosure';

    /**
     * Used to indicate an origin that will be used to fetch required
     *       resources for the link context, and that the user agent ought to resolve
     *       as early as possible.
     */
    case usedtoindicateanoriginthatwillbeusedtofetchrequiredresourcesforthelinkcontextandthattheuseragentoughttoresolveasearlyaspossible = 'dns-prefetch';

    /**
     * Refers to a resource whose available representations
     *       are byte-for-byte identical with the corresponding representations of
     *       the context IRI.
     */
    case referst_oa_resourcewhoseavailablerepresentationsarebyteforbyteidenticalwiththecorrespondingrepresentationsofthecontextiri = 'duplicate';

    /**
     * Refers to a resource that can be used to edit the
     *       link's context.
     */
    case referst_oa_resourcethatcanbeusedtoeditthelinkscontext = 'edit';

    /**
     * The target IRI points to a resource where a submission form for
     *       editing associated resource can be obtained.
     */
    case thetargetiripointst_oa_resourcewhereasubmissionformforeditingassociatedresourcecanbeobtained = 'edit-form';

    /**
     * Refers to a resource that can be used to edit media
     *       associated with the link's context.
     */
    case referst_oa_resourcethatcanbeusedtoeditmediaassociatedwiththelinkscontext = 'edit-media';

    /**
     * Identifies a related resource that is potentially
     *       large and might require special handling.
     */
    case identifie_sa_relatedresourcethatispotentiallylargeandmightrequirespecialhandling = 'enclosure';

    /** Refers to a resource that is not part of the same site as the current context. */
    case referst_oa_resourcethatisnotpartofthesamesiteasthecurrentcontext = 'external';

    /**
     * An IRI that refers to the furthest preceding resource
     *     in a series of resources.
     */
    case anirithatreferstothefurthestprecedingresourcei_na_seriesofresources = 'first';

    /** Refers to a glossary of terms. */
    case referst_oa_glossaryofterms = 'glossary';

    /** Refers to context-sensitive help. */
    case referstocontextsensitivehelp = 'help';

    /**
     * Refers to a resource hosted by the server indicated by
     *       the link context.
     */
    case referst_oa_resourcehostedbytheserverindicatedbythelinkcontext = 'hosts';

    /**
     * Refers to a hub that enables registration for
     *     notification of updates to the context.
     */
    case referst_oa_hubthatenablesregistrationfornotificationofupdatestothecontext = 'hub';

    /** Refers to an icon representing the link's context. */
    case referstoaniconrepresentingthelin_ks_context = 'icon';

    /** Refers to an index. */
    case referstoanindex = 'index';

    /** refers to a resource associated with a time interval that ends before the beginning of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalthatendsbeforethebeginningofthetimeintervalassociatedwiththecontextresource = 'intervalAfter';

    /** refers to a resource associated with a time interval that begins after the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalthatbeginsaftertheendofthetimeintervalassociatedwiththecontextresource = 'intervalBefore';

    /** refers to a resource associated with a time interval that begins after the beginning of the time interval associated with the context resource, and ends before the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalthatbeginsafterthebeginningofthetimeintervalassociatedwiththecontextresourceandendsbeforetheendofthetimeintervalassociatedwiththecontextresource = 'intervalContains';

    /** refers to a resource associated with a time interval that begins after the end of the time interval associated with the context resource, or ends before the beginning of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalthatbeginsaftertheendofthetimeintervalassociatedwiththecontextresourceorendsbeforethebeginningofthetimeintervalassociatedwiththecontextresource = 'intervalDisjoint';

    /** refers to a resource associated with a time interval that begins before the beginning of the time interval associated with the context resource, and ends after the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalthatbeginsbeforethebeginningofthetimeintervalassociatedwiththecontextresourceandendsaftertheendofthetimeintervalassociatedwiththecontextresource = 'intervalDuring';

    /** refers to a resource associated with a time interval whose beginning coincides with the beginning of the time interval associated with the context resource, and whose end coincides with the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalwhosebeginningcoincideswiththebeginningofthetimeintervalassociatedwiththecontextresourceandwhoseendcoincideswiththeendofthetimeintervalassociatedwiththecontextresource = 'intervalEquals';

    /** refers to a resource associated with a time interval that begins after the beginning of the time interval associated with the context resource, and whose end coincides with the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalthatbeginsafterthebeginningofthetimeintervalassociatedwiththecontextresourceandwhoseendcoincideswiththeendofthetimeintervalassociatedwiththecontextresource = 'intervalFinishedBy';

    /** refers to a resource associated with a time interval that begins before the beginning of the time interval associated with the context resource, and whose end coincides with the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalthatbeginsbeforethebeginningofthetimeintervalassociatedwiththecontextresourceandwhoseendcoincideswiththeendofthetimeintervalassociatedwiththecontextresource = 'intervalFinishes';

    /** refers to a resource associated with a time interval that begins before or is coincident with the beginning of the time interval associated with the context resource, and ends after or is coincident with the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalthatbeginsbeforeoriscoincidentwiththebeginningofthetimeintervalassociatedwiththecontextresourceandendsafteroriscoincidentwiththeendofthetimeintervalassociatedwiththecontextresource = 'intervalIn';

    /** refers to a resource associated with a time interval whose beginning coincides with the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalwhosebeginningcoincideswiththeendofthetimeintervalassociatedwiththecontextresource = 'intervalMeets';

    /** refers to a resource associated with a time interval whose end coincides with the beginning of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalwhoseendcoincideswiththebeginningofthetimeintervalassociatedwiththecontextresource = 'intervalMetBy';

    /** refers to a resource associated with a time interval that begins before the beginning of the time interval associated with the context resource, and ends after the beginning of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalthatbeginsbeforethebeginningofthetimeintervalassociatedwiththecontextresourceandendsafterthebeginningofthetimeintervalassociatedwiththecontextresource = 'intervalOverlappedBy';

    /** refers to a resource associated with a time interval that begins before the end of the time interval associated with the context resource, and ends after the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalthatbeginsbeforetheendofthetimeintervalassociatedwiththecontextresourceandendsaftertheendofthetimeintervalassociatedwiththecontextresource = 'intervalOverlaps';

    /** refers to a resource associated with a time interval whose beginning coincides with the beginning of the time interval associated with the context resource, and ends before the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalwhosebeginningcoincideswiththebeginningofthetimeintervalassociatedwiththecontextresourceandendsbeforetheendofthetimeintervalassociatedwiththecontextresource = 'intervalStartedBy';

    /** refers to a resource associated with a time interval whose beginning coincides with the beginning of the time interval associated with the context resource, and ends after the end of the time interval associated with the context resource */
    case referst_oa_resourceassociatedwithatimeintervalwhosebeginningcoincideswiththebeginningofthetimeintervalassociatedwiththecontextresourceandendsaftertheendofthetimeintervalassociatedwiththecontextresource = 'intervalStarts';

    /** The target IRI points to a resource that is a member of the collection represented by the context IRI. */
    case thetargetiripointst_oa_resourcethatisamemberofthecollectionrepresentedbythecontextiri = 'item';

    /**
     * An IRI that refers to the furthest following resource
     *       in a series of resources.
     */
    case anirithatreferstothefurthestfollowingresourcei_na_seriesofresources = 'last';

    /**
     * Points to a resource containing the latest (e.g.,
     *       current) version of the context.
     */
    case pointst_oa_resourcecontainingthelatestegcurrentversionofthecontext = 'latest-version';

    /** Refers to a license associated with this context. */
    case referst_oa_licenseassociatedwiththiscontext = 'license';

    /**
     * The link target of a link with the "linkset" relation
     *       type provides a set of links, including links in which the link
     *       context of the link participates.
     */
    case thelinktargeto_fa_linkwiththelinksetrelationtypeprovidesasetoflinksincludinglinksinwhichthelinkcontextofthelinkparticipates = 'linkset';

    /**
     * Refers to further information about the link's context,
     *       expressed as a LRDD ("Link-based Resource Descriptor Document")
     *       resource.  See  for information about
     *       processing this relation type in host-meta documents. When used
     *       elsewhere, it refers to additional links and other metadata.
     *       Multiple instances indicate additional LRDD resources. LRDD
     *       resources MUST have an "application/xrd+xml" representation, and
     *       MAY have others.
     */
    case referstofurtherinformationaboutthelin_ks_contextexpressedasalrddlinkbasedresourcedescriptordocumentresourceseeforinformationaboutprocessingthisrelationtypeinhostmetadocumentswhenusedelsewhereitreferstoadditionallinksandothermetadatamultipleinstancesindicateadditionallrddresourceslrddresourcesmusthaveanapplicationxrdxmlrepresentationandmayhaveothers = 'lrdd';

    /** Links to a manifest file for the context. */
    case linkst_oa_manifestfileforthecontext = 'manifest';

    /** Refers to a mask that can be applied to the icon for the context. */
    case referst_oa_maskthatcanbeappliedtotheiconforthecontext = 'mask-icon';

    /** Refers to a feed of personalised media recommendations relevant to the link context. */
    case referst_oa_feedofpersonalisedmediarecommendationsrelevanttothelinkcontext = 'media-feed';

    /** The Target IRI points to a Memento, a fixed resource that will not change state anymore. */
    case thetargetiripointst_oa_mementoafixedresourcethatwillnotchangestateanymore = 'memento';

    /** Links to the context's Micropub endpoint. */
    case linkstothecontex_ts_micropubendpoint = 'micropub';

    /** Refers to a module that the user agent is to preemptively fetch and store for use in the current context. */
    case referst_oa_modulethattheuseragentistopreemptivelyfetchandstoreforuseinthecurrentcontext = 'modulepreload';

    /**
     * Refers to a resource that can be used to monitor changes in an HTTP resource.
     */
    case referst_oa_resourcethatcanbeusedtomonitorchangesinanhttpresource = 'monitor';

    /**
     * Refers to a resource that can be used to monitor changes in a specified group of HTTP resources.
     */
    case referst_oa_resourcethatcanbeusedtomonitorchangesinaspecifiedgroupofhttpresources = 'monitor-group';

    /**
     * Indicates that the link's context is a part of a series, and
     *       that the next in the series is the link target.
     */
    case indicatesthatthelin_ks_contextisapartofaseriesandthatthenextintheseriesisthelinktarget = 'next';

    /** Refers to the immediately following archive resource. */
    case referstotheimmediatelyfollowingarchiveresource = 'next-archive';

    /** Indicates that the context’s original author or publisher does not endorse the link target. */
    case indicatesthatthecontex_ts_originalauthororpublisherdoesnotendorsethelinktarget = 'nofollow';

    /** Indicates that any newly created top-level browsing context which results from following the link will not be an auxiliary browsing context. */
    case indicatesthatanynewlycreatedtoplevelbrowsingcontextwhichresultsfromfollowingthelinkwillnotbeanauxiliarybrowsingcontext = 'noopener';

    /** Indicates that no referrer information is to be leaked when following the link. */
    case indicatesthatnoreferrerinformationistobeleakedwhenfollowingthelink = 'noreferrer';

    /** Indicates that any newly created top-level browsing context which results from following the link will be an auxiliary browsing context. */
    case indicatesthatanynewlycreatedtoplevelbrowsingcontextwhichresultsfromfollowingthelinkwillbeanauxiliarybrowsingcontext = 'opener';

    /** Refers to an OpenID Authentication server on which the context relies for an assertion that the end user controls an Identifier. */
    case referstoanopenidauthenticationserveronwhichthecontextreliesforanassertionthattheendusercontrolsanidentifier = 'openid2.local_id';

    /** Refers to a resource which accepts OpenID Authentication protocol messages for the context. */
    case referst_oa_resourcewhichacceptsopenidauthenticationprotocolmessagesforthecontext = 'openid2.provider';

    /** The Target IRI points to an Original Resource. */
    case thetargetiripointstoanoriginalresource = 'original';

    /** Refers to a P3P privacy policy for the context. */
    case referst_oa_p3_pprivacypolicyforthecontext = 'P3Pv1';

    /** Indicates a resource where payment is accepted. */
    case indicate_sa_resourcewherepaymentisaccepted = 'payment';

    /** Gives the address of the pingback resource for the link context. */
    case givestheaddressofthepingbackresourceforthelinkcontext = 'pingback';

    /**
     * Used to indicate an origin that will be used to fetch required
     *       resources for the link context. Initiating an early connection, which
     *       includes the DNS lookup, TCP handshake, and optional TLS negotiation,
     *       allows the user agent to mask the high latency costs of establishing a
     *       connection.
     */
    case usedtoindicateanoriginthatwillbeusedtofetchrequiredresourcesforthelinkcontextinitiatinganearlyconnectionwhichincludesthednslookuptcphandshakeandoptionaltlsnegotiationallowstheuseragenttomaskthehighlatencycostsofestablishin_ga_connection = 'preconnect';

    /**
     * Points to a resource containing the predecessor
     *       version in the version history.
     */
    case pointst_oa_resourcecontainingthepredecessorversionintheversionhistory = 'predecessor-version';

    /**
     * The prefetch link relation type is used to identify a resource
     *       that might be required by the next navigation from the link context, and
     *       that the user agent ought to fetch, such that the user agent can deliver a
     *       faster response once the resource is requested in the future.
     */
    case theprefetchlinkrelationtypeisusedtoidentif_ya_resourcethatmightberequiredbythenextnavigationfromthelinkcontextandthattheuseragentoughttofetchsuchthattheuseragentcandeliverafasterresponseoncetheresourceisrequestedinthefuture = 'prefetch';

    /**
     * Refers to a resource that should be loaded early in the
     *       processing of the link's context, without blocking rendering.
     */
    case referst_oa_resourcethatshouldbeloadedearlyintheprocessingofthelinkscontextwithoutblockingrendering = 'preload';

    /**
     * Used to identify a resource that might be required by the next
     *       navigation from the link context, and that the user agent ought to fetch
     *       and execute, such that the user agent can deliver a faster response once
     *       the resource is requested in the future.
     */
    case usedtoidentif_ya_resourcethatmightberequiredbythenextnavigationfromthelinkcontextandthattheuseragentoughttofetchandexecutesuchthattheuseragentcandeliverafasterresponseoncetheresourceisrequestedinthefuture = 'prerender';

    /**
     * Indicates that the link's context is a part of a series, and
     *       that the previous in the series is the link target.
     */
    case indicatesthatthelin_ks_contextisapartofaseriesandthatthepreviousintheseriesisthelinktarget = 'prev';

    /** Refers to a resource that provides a preview of the link's context. */
    case referst_oa_resourcethatprovidesapreviewofthelinkscontext = 'preview';

    /**
     * Refers to the previous resource in an ordered series
     *       of resources.  Synonym for "prev".
     */
    case referstothepreviousresourceinanorderedseriesofresourcessynonymforprev = 'previous';

    /** Refers to the immediately preceding archive resource. */
    case referstotheimmediatelyprecedingarchiveresource = 'prev-archive';

    /** Refers to a privacy policy associated with the link's context. */
    case referst_oa_privacypolicyassociatedwiththelinkscontext = 'privacy-policy';

    /**
     * Identifying that a resource representation conforms
     * to a certain profile, without affecting the non-profile semantics
     * of the resource representation.
     */
    case identifyingtha_ta_resourcerepresentationconformstoacertainprofilewithoutaffectingthenonprofilesemanticsoftheresourcerepresentation = 'profile';

    /**
     * Links to a publication manifest. A manifest represents
     *       structured information about a publication, such as informative metadata,
     *       a list of resources, and a default reading order.
     */
    case linkst_oa_publicationmanifestamanifestrepresentsstructuredinformationaboutapublicationsuchasinformativemetadataalistofresourcesandadefaultreadingorder = 'publication';

    /** Identifies a related resource. */
    case identifie_sa_relatedresource = 'related';

    /**
     * Identifies the root of RESTCONF API as configured on this HTTP server.
     *       The "restconf" relation defines the root of the API defined in RFC8040.
     *       Subsequent revisions of RESTCONF will use alternate relation values to support
     *       protocol versioning.
     */
    case identifiestherootofrestconfapiasconfiguredonthishttpservertherestconfrelationdefinestherootoftheapidefinedinrfc8040_subsequentrevisionsofrestconfwillusealternaterelationvaluestosupportprotocolversioning = 'restconf';

    /**
     * Identifies a resource that is a reply to the context
     *       of the link.
     */
    case identifie_sa_resourcethatisareplytothecontextofthelink = 'replies';

    /**
     * The resource identified by the link target provides an input value to an
     *     instance of a rule, where the resource which represents the rule instance is
     *     identified by the link context.
     */
    case theresourceidentifiedbythelinktargetprovidesaninputvaluetoaninstanceo_fa_rulewheretheresourcewhichrepresentstheruleinstanceisidentifiedbythelinkcontext = 'ruleinput';

    /**
     * Refers to a resource that can be used to search through
     *       the link's context and related resources.
     */
    case referst_oa_resourcethatcanbeusedtosearchthroughthelinkscontextandrelatedresources = 'search';

    /** Refers to a section in a collection of resources. */
    case referst_oa_sectioninacollectionofresources = 'section';

    /**
     * Conveys an identifier for the link's context.
     */
    case conveysanidentifierforthelin_ks_context = 'self';

    /**
     * Indicates a URI that can be used to retrieve a
     *       service document.
     */
    case indicate_sa_urithatcanbeusedtoretrieveaservicedocument = 'service';

    /**
     * Identifies service description for the context that
     *       is primarily intended for consumption by machines.
     */
    case identifiesservicedescriptionforthecontextthatisprimarilyintendedforconsumptionbymachines = 'service-desc';

    /**
     * Identifies service documentation for the context that
     *       is primarily intended for human consumption.
     */
    case identifiesservicedocumentationforthecontextthatisprimarilyintendedforhumanconsumption = 'service-doc';

    /**
     * Identifies general metadata for the context that is
     *       primarily intended for consumption by machines.
     */
    case identifiesgeneralmetadataforthecontextthatisprimarilyintendedforconsumptionbymachines = 'service-meta';

    /**
     * Refers to a resource that is within a context that is
     * 		sponsored (such as advertising or another compensation agreement).
     */
    case referst_oa_resourcethatiswithinacontextthatissponsoredsuchasadvertisingoranothercompensationagreement = 'sponsored';

    /**
     * Refers to the first resource in a collection of
     *       resources.
     */
    case referstothefirstresourcei_na_collectionofresources = 'start';

    /**
     * Identifies a resource that represents the context's
     *       status.
     */
    case identifie_sa_resourcethatrepresentsthecontextsstatus = 'status';

    /** Refers to a stylesheet. */
    case referst_oa_stylesheet = 'stylesheet';

    /**
     * Refers to a resource serving as a subsection in a
     *       collection of resources.
     */
    case referst_oa_resourceservingasasubsectioninacollectionofresources = 'subsection';

    /**
     * Points to a resource containing the successor version
     *       in the version history.
     */
    case pointst_oa_resourcecontainingthesuccessorversionintheversionhistory = 'successor-version';

    /**
     * Identifies a resource that provides information about
     *       the context's retirement policy.
     */
    case identifie_sa_resourcethatprovidesinformationaboutthecontextsretirementpolicy = 'sunset';

    /**
     * Gives a tag (identified by the given address) that applies to
     *       the current document.
     */
    case give_sa_tagidentifiedbythegivenaddressthatappliestothecurrentdocument = 'tag';

    /** Refers to the terms of service associated with the link's context. */
    case referstothetermsofserviceassociatedwiththelin_ks_context = 'terms-of-service';

    /** The Target IRI points to a TimeGate for an Original Resource. */
    case thetargetiripointst_oa_timegateforanoriginalresource = 'timegate';

    /** The Target IRI points to a TimeMap for an Original Resource. */
    case thetargetiripointst_oa_timemapforanoriginalresource = 'timemap';

    /** Refers to a resource identifying the abstract semantic type of which the link's context is considered to be an instance. */
    case referst_oa_resourceidentifyingtheabstractsemantictypeofwhichthelinkscontextisconsideredtobeaninstance = 'type';

    /**
     * Refers to a resource that is within a context that is User Generated Content.
     */
    case referst_oa_resourcethatiswithinacontextthatisusergeneratedcontent = 'ugc';

    /**
     * Refers to a parent document in a hierarchy of
     *       documents.
     */
    case referst_oa_parentdocumentinahierarchyofdocuments = 'up';

    /**
     * Points to a resource containing the version history
     *       for the context.
     */
    case pointst_oa_resourcecontainingtheversionhistoryforthecontext = 'version-history';

    /**
     * Identifies a resource that is the source of the
     *       information in the link's context.
     */
    case identifie_sa_resourcethatisthesourceoftheinformationinthelinkscontext = 'via';

    /**
     * Identifies a target URI that supports the Webmention protocol.
     *     This allows clients that mention a resource in some form of publishing process
     *     to contact that endpoint and inform it that this resource has been mentioned.
     */
    case identifie_sa_targeturithatsupportsthewebmentionprotocolthisallowsclientsthatmentionaresourceinsomeformofpublishingprocesstocontactthatendpointandinformitthatthisresourcehasbeenmentioned = 'webmention';

    /** Points to a working copy for this resource. */
    case pointst_oa_workingcopyforthisresource = 'working-copy';

    /**
     * Points to the versioned resource from which this
     *       working copy was obtained.
     */
    case pointstotheversionedresourcefromwhichthisworkingcopywasobtained = 'working-copy-of';
}

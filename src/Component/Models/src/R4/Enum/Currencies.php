<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CurrencyCode
 * URL: http://hl7.org/fhir/ValueSet/currencies
 * Version: 4.0.1
 * Description: Currency codes from ISO 4217 (see https://www.iso.org/iso-4217-currency-codes.html)
 */
enum Currencies: string
{
    /** Andorran Peseta */
    case andorranpeseta = 'ADP';

    /** United Arab Emirates Dirham */
    case unitedarabemiratesdirham = 'AED';

    /** Afghan Afghani (1927–2002) */
    case afghanafghani19272002 = 'AFA';

    /** Afghan Afghani */
    case afghanafghani = 'AFN';

    /** Albanian Lek (1946–1965) */
    case albanianlek19461965 = 'ALK';

    /** Albanian Lek */
    case albanianlek = 'ALL';

    /** Armenian Dram */
    case armeniandram = 'AMD';

    /** Netherlands Antillean Guilder */
    case netherlandsantilleanguilder = 'ANG';

    /** Angolan Kwanza */
    case angolankwanza = 'AOA';

    /** Angolan Kwanza (1977–1991) */
    case angolankwanza19771991 = 'AOK';

    /** Angolan New Kwanza (1990–2000) */
    case angolannewkwanza19902000 = 'AON';

    /** Angolan Readjusted Kwanza (1995–1999) */
    case angolanreadjustedkwanza19951999 = 'AOR';

    /** Argentine Austral */
    case argentineaustral = 'ARA';

    /** Argentine Peso Ley (1970–1983) */
    case argentinepesoley19701983 = 'ARL';

    /** Argentine Peso (1881–1970) */
    case argentinepeso18811970 = 'ARM';

    /** Argentine Peso (1983–1985) */
    case argentinepeso19831985 = 'ARP';

    /** Argentine Peso */
    case argentinepeso = 'ARS';

    /** Austrian Schilling */
    case austrianschilling = 'ATS';

    /** Australian Dollar */
    case australiandollar = 'AUD';

    /** Aruban Florin */
    case arubanflorin = 'AWG';

    /** Azerbaijani Manat (1993–2006) */
    case azerbaijanimanat19932006 = 'AZM';

    /** Azerbaijani Manat */
    case azerbaijanimanat = 'AZN';

    /** Bosnia-Herzegovina Dinar (1992–1994) */
    case bosniaherzegovinadinar19921994 = 'BAD';

    /** Bosnia-Herzegovina Convertible Marka */
    case bosniaherzegovinaconvertiblemarka = 'BAM';

    /** Bosnia-Herzegovina New Dinar (1994–1997) */
    case bosniaherzegovinanewdinar19941997 = 'BAN';

    /** Barbados Dollar */
    case barbadosdollar = 'BBD';

    /** Bangladeshi Taka */
    case bangladeshitaka = 'BDT';

    /** Belgian Franc (convertible) */
    case belgianfrancconvertible = 'BEC';

    /** Belgian Franc */
    case belgianfranc = 'BEF';

    /** Belgian Franc (financial) */
    case belgianfrancfinancial = 'BEL';

    /** Bulgarian Hard Lev */
    case bulgarianhardlev = 'BGL';

    /** Bulgarian Socialist Lev */
    case bulgariansocialistlev = 'BGM';

    /** Bulgarian Lev */
    case bulgarianlev = 'BGN';

    /** Bulgarian Lev (1879–1952) */
    case bulgarianlev18791952 = 'BGO';

    /** Bahraini Dinar */
    case bahrainidinar = 'BHD';

    /** Burundian Franc */
    case burundianfranc = 'BIF';

    /** Bermuda Dollar */
    case bermudadollar = 'BMD';

    /** Brunei Dollar */
    case bruneidollar = 'BND';

    /** Bolivian boliviano */
    case bolivianboliviano = 'BOB';

    /** Bolivian Boliviano (1863–1963) */
    case bolivianboliviano18631963 = 'BOL';

    /** Bolivian Peso */
    case bolivianpeso = 'BOP';

    /** Bolivian Mvdol */
    case bolivianmvdol = 'BOV';

    /** Brazilian New Cruzeiro (1967–1986) */
    case braziliannewcruzeiro19671986 = 'BRB';

    /** Brazilian Cruzado (1986–1989) */
    case braziliancruzado19861989 = 'BRC';

    /** Brazilian Cruzeiro (1990–1993) */
    case braziliancruzeiro19901993 = 'BRE';

    /** Brazilian Real */
    case brazilianreal = 'BRL';

    /** Brazilian New Cruzado (1989–1990) */
    case braziliannewcruzado19891990 = 'BRN';

    /** Brazilian Cruzeiro (1993–1994) */
    case braziliancruzeiro19931994 = 'BRR';

    /** Brazilian Cruzeiro (1942–1967) */
    case braziliancruzeiro19421967 = 'BRZ';

    /** Bahamian Dollar */
    case bahamiandollar = 'BSD';

    /** Bhutanese Ngultrum */
    case bhutanesengultrum = 'BTN';

    /** Burmese Kyat */
    case burmesekyat = 'BUK';

    /** Botswanan Pula */
    case botswananpula = 'BWP';

    /** Belarusian Ruble (1994–1999) */
    case belarusianruble19941999 = 'BYB';

    /** Belarusian Ruble */
    case belarusianruble = 'BYN';

    /** Belarusian Ruble (2000–2016) */
    case belarusianruble20002016 = 'BYR';

    /** Belize Dollar */
    case belizedollar = 'BZD';

    /** Canadian Dollar */
    case canadiandollar = 'CAD';

    /** Congolese Franc */
    case congolesefranc = 'CDF';

    /** WIR Euro */
    case wireuro = 'CHE';

    /** Swiss Franc */
    case swissfranc = 'CHF';

    /** WIR Franc */
    case wirfranc = 'CHW';

    /** Chilean Escudo */
    case chileanescudo = 'CLE';

    /** Chilean Unit of Account (UF) */
    case chileanunitofaccountuf = 'CLF';

    /** Chilean Peso */
    case chileanpeso = 'CLP';

    /** Chinese Yuan (offshore) */
    case chineseyuanoffshore = 'CNH';

    /** Chinese People’s Bank Dollar */
    case chinesepeopl_es_bankdollar = 'CNX';

    /** Chinese Yuan */
    case chineseyuan = 'CNY';

    /** Colombian Peso */
    case colombianpeso = 'COP';

    /** Colombian Real Value Unit */
    case colombianrealvalueunit = 'COU';

    /** Costa Rican Colón */
    case costaricancolón = 'CRC';

    /** Serbian Dinar (2002–2006) */
    case serbiandinar20022006 = 'CSD';

    /** Czechoslovak Hard Koruna */
    case czechoslovakhardkoruna = 'CSK';

    /** Cuban Convertible Peso */
    case cubanconvertiblepeso = 'CUC';

    /** Cuban Peso */
    case cubanpeso = 'CUP';

    /** Cape Verdean Escudo */
    case capeverdeanescudo = 'CVE';

    /** Cypriot Pound */
    case cypriotpound = 'CYP';

    /** Czech Koruna */
    case czechkoruna = 'CZK';

    /** East German Mark */
    case eastgermanmark = 'DDM';

    /** German Mark */
    case germanmark = 'DEM';

    /** Djiboutian Franc */
    case djiboutianfranc = 'DJF';

    /** Danish Krone */
    case danishkrone = 'DKK';

    /** Dominican Peso */
    case dominicanpeso = 'DOP';

    /** Algerian Dinar */
    case algeriandinar = 'DZD';

    /** Ecuadorian Sucre */
    case ecuadoriansucre = 'ECS';

    /** Ecuadorian Unit of Constant Value */
    case ecuadorianunitofconstantvalue = 'ECV';

    /** Estonian Kroon */
    case estoniankroon = 'EEK';

    /** Egyptian Pound */
    case egyptianpound = 'EGP';

    /** Eritrean Nakfa */
    case eritreannakfa = 'ERN';

    /** Spanish Peseta (A account) */
    case spanishpeset_aa_account = 'ESA';

    /** Spanish Peseta (convertible account) */
    case spanishpesetaconvertibleaccount = 'ESB';

    /** Spanish Peseta */
    case spanishpeseta = 'ESP';

    /** Ethiopian Birr */
    case ethiopianbirr = 'ETB';

    /** Euro */
    case euro = 'EUR';

    /** Finnish Markka */
    case finnishmarkka = 'FIM';

    /** Fijian Dollar */
    case fijiandollar = 'FJD';

    /** Falkland Islands Pound */
    case falklandislandspound = 'FKP';

    /** French Franc */
    case frenchfranc = 'FRF';

    /** British Pound */
    case britishpound = 'GBP';

    /** Georgian Kupon Larit */
    case georgiankuponlarit = 'GEK';

    /** Georgian Lari */
    case georgianlari = 'GEL';

    /** Ghanaian Cedi (1979–2007) */
    case ghanaiancedi19792007 = 'GHC';

    /** Ghanaian Cedi */
    case ghanaiancedi = 'GHS';

    /** Gibraltar Pound */
    case gibraltarpound = 'GIP';

    /** Gambian Dalasi */
    case gambiandalasi = 'GMD';

    /** Guinean Franc */
    case guineanfranc = 'GNF';

    /** Guinean Syli */
    case guineansyli = 'GNS';

    /** Equatorial Guinean Ekwele */
    case equatorialguineanekwele = 'GQE';

    /** Greek Drachma */
    case greekdrachma = 'GRD';

    /** Guatemalan Quetzal */
    case guatemalanquetzal = 'GTQ';

    /** Portuguese Guinea Escudo */
    case portugueseguineaescudo = 'GWE';

    /** Guinea-Bissau Peso */
    case guineabissaupeso = 'GWP';

    /** Guyanaese Dollar */
    case guyanaesedollar = 'GYD';

    /** Hong Kong Dollar */
    case hongkongdollar = 'HKD';

    /** Honduran Lempira */
    case honduranlempira = 'HNL';

    /** Croatian Dinar */
    case croatiandinar = 'HRD';

    /** Croatian Kuna */
    case croatiankuna = 'HRK';

    /** Haitian Gourde */
    case haitiangourde = 'HTG';

    /** Hungarian Forint */
    case hungarianforint = 'HUF';

    /** Indonesian Rupiah */
    case indonesianrupiah = 'IDR';

    /** Irish Pound */
    case irishpound = 'IEP';

    /** Israeli Pound */
    case israelipound = 'ILP';

    /** Israeli Shekel (1980–1985) */
    case israelishekel19801985 = 'ILR';

    /** Israeli Shekel */
    case israelishekel = 'ILS';

    /** Indian Rupee */
    case indianrupee = 'INR';

    /** Iraqi Dinar */
    case iraqidinar = 'IQD';

    /** Iranian Rial */
    case iranianrial = 'IRR';

    /** Icelandic Króna (1918–1981) */
    case icelandickróna19181981 = 'ISJ';

    /** Icelandic Króna */
    case icelandickróna = 'ISK';

    /** Italian Lira */
    case italianlira = 'ITL';

    /** Jamaican Dollar */
    case jamaicandollar = 'JMD';

    /** Jordanian Dinar */
    case jordaniandinar = 'JOD';

    /** Japanese Yen */
    case japaneseyen = 'JPY';

    /** Kenyan Shilling */
    case kenyanshilling = 'KES';

    /** Kyrgyz Som */
    case kyrgyzsom = 'KGS';

    /** Cambodian Riel */
    case cambodianriel = 'KHR';

    /** Comorian Franc */
    case comorianfranc = 'KMF';

    /** North Korean Won */
    case northkoreanwon = 'KPW';

    /** South Korean Hwan (1953–1962) */
    case southkoreanhwan19531962 = 'KRH';

    /** South Korean Won (1945–1953) */
    case southkoreanwon19451953 = 'KRO';

    /** South Korean Won */
    case southkoreanwon = 'KRW';

    /** Kuwaiti Dinar */
    case kuwaitidinar = 'KWD';

    /** Cayman Islands Dollar */
    case caymanislandsdollar = 'KYD';

    /** Kazakhstani Tenge */
    case kazakhstanitenge = 'KZT';

    /** Laotian Kip */
    case laotiankip = 'LAK';

    /** Lebanese Pound */
    case lebanesepound = 'LBP';

    /** Sri Lankan Rupee */
    case srilankanrupee = 'LKR';

    /** Liberian Dollar */
    case liberiandollar = 'LRD';

    /** Lesotho Loti */
    case lesotholoti = 'LSL';

    /** Lithuanian Litas */
    case lithuanianlitas = 'LTL';

    /** Lithuanian Talonas */
    case lithuaniantalonas = 'LTT';

    /** Luxembourgian Convertible Franc */
    case luxembourgianconvertiblefranc = 'LUC';

    /** Luxembourgian Franc */
    case luxembourgianfranc = 'LUF';

    /** Luxembourg Financial Franc */
    case luxembourgfinancialfranc = 'LUL';

    /** Latvian Lats */
    case latvianlats = 'LVL';

    /** Latvian Ruble */
    case latvianruble = 'LVR';

    /** Libyan Dinar */
    case libyandinar = 'LYD';

    /** Moroccan Dirham */
    case moroccandirham = 'MAD';

    /** Moroccan Franc */
    case moroccanfranc = 'MAF';

    /** Monegasque Franc */
    case monegasquefranc = 'MCF';

    /** Moldovan Cupon */
    case moldovancupon = 'MDC';

    /** Moldovan Leu */
    case moldovanleu = 'MDL';

    /** Malagasy Ariary */
    case malagasyariary = 'MGA';

    /** Malagasy Franc */
    case malagasyfranc = 'MGF';

    /** Macedonian Denar */
    case macedoniandenar = 'MKD';

    /** Macedonian Denar (1992–1993) */
    case macedoniandenar19921993 = 'MKN';

    /** Malian Franc */
    case malianfranc = 'MLF';

    /** Myanmar Kyat */
    case myanmarkyat = 'MMK';

    /** Mongolian Tugrik */
    case mongoliantugrik = 'MNT';

    /** Macanese Pataca */
    case macanesepataca = 'MOP';

    /** Mauritanian Ouguiya (1973–2017) */
    case mauritanianouguiya19732017 = 'MRO';

    /** Mauritanian Ouguiya */
    case mauritanianouguiya = 'MRU';

    /** Maltese Lira */
    case malteselira = 'MTL';

    /** Maltese Pound */
    case maltesepound = 'MTP';

    /** Mauritian Rupee */
    case mauritianrupee = 'MUR';

    /** Maldivian Rupee (1947–1981) */
    case maldivianrupee19471981 = 'MVP';

    /** Maldivian Rufiyaa */
    case maldivianrufiyaa = 'MVR';

    /** Malawian Kwacha */
    case malawiankwacha = 'MWK';

    /** Mexican Peso */
    case mexicanpeso = 'MXN';

    /** Mexican Silver Peso (1861–1992) */
    case mexicansilverpeso18611992 = 'MXP';

    /** Mexican Investment Unit */
    case mexicaninvestmentunit = 'MXV';

    /** Malaysian Ringgit */
    case malaysianringgit = 'MYR';

    /** Mozambican Escudo */
    case mozambicanescudo = 'MZE';

    /** Mozambican Metical (1980–2006) */
    case mozambicanmetical19802006 = 'MZM';

    /** Mozambican Metical */
    case mozambicanmetical = 'MZN';

    /** Namibian Dollar */
    case namibiandollar = 'NAD';

    /** Nigerian Naira */
    case nigeriannaira = 'NGN';

    /** Nicaraguan Córdoba (1988–1991) */
    case nicaraguancórdoba19881991 = 'NIC';

    /** Nicaraguan Córdoba */
    case nicaraguancórdoba = 'NIO';

    /** Dutch Guilder */
    case dutchguilder = 'NLG';

    /** Norwegian Krone */
    case norwegiankrone = 'NOK';

    /** Nepalese Rupee */
    case nepaleserupee = 'NPR';

    /** New Zealand Dollar */
    case newzealanddollar = 'NZD';

    /** Omani Rial */
    case omanirial = 'OMR';

    /** Panamanian Balboa */
    case panamanianbalboa = 'PAB';

    /** Peruvian Inti */
    case peruvianinti = 'PEI';

    /** Peruvian Sol */
    case peruviansol = 'PEN';

    /** Peruvian Sol (1863–1965) */
    case peruviansol18631965 = 'PES';

    /** Papua New Guinean Kina */
    case papuanewguineankina = 'PGK';

    /** Philippine Peso */
    case philippinepeso = 'PHP';

    /** Pakistani Rupee */
    case pakistanirupee = 'PKR';

    /** Polish Zloty */
    case polishzloty = 'PLN';

    /** Polish Zloty (1950–1995) */
    case polishzloty19501995 = 'PLZ';

    /** Portuguese Escudo */
    case portugueseescudo = 'PTE';

    /** Paraguayan Guarani */
    case paraguayanguarani = 'PYG';

    /** Qatari Riyal */
    case qataririyal = 'QAR';

    /** Rhodesian Dollar */
    case rhodesiandollar = 'RHD';

    /** Romanian Leu (1952–2006) */
    case romanianleu19522006 = 'ROL';

    /** Romanian Leu */
    case romanianleu = 'RON';

    /** Serbian Dinar */
    case serbiandinar = 'RSD';

    /** Russian Ruble */
    case russianruble = 'RUB';

    /** Russian Ruble (1991–1998) */
    case russianruble19911998 = 'RUR';

    /** Rwandan Franc */
    case rwandanfranc = 'RWF';

    /** Saudi Riyal */
    case saudiriyal = 'SAR';

    /** Solomon Islands Dollar */
    case solomonislandsdollar = 'SBD';

    /** Seychellois Rupee */
    case seychelloisrupee = 'SCR';

    /** Sudanese Dinar (1992–2007) */
    case sudanesedinar19922007 = 'SDD';

    /** Sudanese Pound */
    case sudanesepound = 'SDG';

    /** Sudanese Pound (1957–1998) */
    case sudanesepound19571998 = 'SDP';

    /** Swedish Krona */
    case swedishkrona = 'SEK';

    /** Singapore Dollar */
    case singaporedollar = 'SGD';

    /** St. Helena Pound */
    case sthelenapound = 'SHP';

    /** Slovenian Tolar */
    case sloveniantolar = 'SIT';

    /** Slovak Koruna */
    case slovakkoruna = 'SKK';

    /** Sierra Leonean Leone */
    case sierraleoneanleone = 'SLE';

    /** Sierra Leonean Leone (1964–2022) */
    case sierraleoneanleone19642022 = 'SLL';

    /** Somali Shilling */
    case somalishilling = 'SOS';

    /** Suriname Dollar */
    case surinamedollar = 'SRD';

    /** Surinamese Guilder */
    case surinameseguilder = 'SRG';

    /** South Sudanese Pound */
    case southsudanesepound = 'SSP';

    /** São Tomé & Príncipe Dobra (1977–2017) */
    case sãotomépríncipedobra19772017 = 'STD';

    /** São Tomé & Príncipe Dobra */
    case sãotomépríncipedobra = 'STN';

    /** Soviet Rouble */
    case sovietrouble = 'SUR';

    /** Salvadoran Colón */
    case salvadorancolón = 'SVC';

    /** Syrian Pound */
    case syrianpound = 'SYP';

    /** Swazi Lilangeni */
    case swazililangeni = 'SZL';

    /** Thai Baht */
    case thaibaht = 'THB';

    /** Tajikistani Ruble */
    case tajikistaniruble = 'TJR';

    /** Tajikistani Somoni */
    case tajikistanisomoni = 'TJS';

    /** Turkmenistani Manat (1993–2009) */
    case turkmenistanimanat19932009 = 'TMM';

    /** Turkmenistani Manat */
    case turkmenistanimanat = 'TMT';

    /** Tunisian Dinar */
    case tunisiandinar = 'TND';

    /** Tongan Paʻanga */
    case tonganpaʻanga = 'TOP';

    /** Timorese Escudo */
    case timoreseescudo = 'TPE';

    /** Turkish Lira (1922–2005) */
    case turkishlira19222005 = 'TRL';

    /** Turkish Lira */
    case turkishlira = 'TRY';

    /** Trinidad & Tobago Dollar */
    case trinidadtobagodollar = 'TTD';

    /** New Taiwan Dollar */
    case newtaiwandollar = 'TWD';

    /** Tanzanian Shilling */
    case tanzanianshilling = 'TZS';

    /** Ukrainian Hryvnia */
    case ukrainianhryvnia = 'UAH';

    /** Ukrainian Karbovanets */
    case ukrainiankarbovanets = 'UAK';

    /** Ugandan Shilling (1966–1987) */
    case ugandanshilling19661987 = 'UGS';

    /** Ugandan Shilling */
    case ugandanshilling = 'UGX';

    /** US Dollar */
    case usdollar = 'USD';

    /** US Dollar (Next day) */
    case usdollarnextday = 'USN';

    /** US Dollar (Same day) */
    case usdollarsameday = 'USS';

    /** Uruguayan Peso (Indexed Units) */
    case uruguayanpesoindexedunits = 'UYI';

    /** Uruguayan Peso (1975–1993) */
    case uruguayanpeso19751993 = 'UYP';

    /** Peso Uruguayo */
    case pesouruguayo = 'UYU';

    /** Uruguayan Nominal Wage Index Unit */
    case uruguayannominalwageindexunit = 'UYW';

    /** Uzbekistani Som */
    case uzbekistanisom = 'UZS';

    /** Venezuelan Bolívar (1871–2008) */
    case venezuelanbolívar18712008 = 'VEB';

    /** Bolívar Soberano */
    case bolívarsoberano = 'VED';

    /** Venezuelan Bolívar (2008–2018) */
    case venezuelanbolívar20082018 = 'VEF';

    /** Venezuelan bolívar */
    case venezuelanbolívar = 'VES';

    /** Vietnamese Dong */
    case vietnamesedong = 'VND';

    /** Vietnamese Dong (1978–1985) */
    case vietnamesedong19781985 = 'VNN';

    /** Vanuatu Vatu */
    case vanuatuvatu = 'VUV';

    /** Samoan Tala */
    case samoantala = 'WST';

    /** Central African CFA Franc */
    case centralafricancfafranc = 'XAF';

    /** East Caribbean Dollar */
    case eastcaribbeandollar = 'XCD';

    /** Caribbean guilder */
    case caribbeanguilder = 'XCG';

    /** European Currency Unit */
    case europeancurrencyunit = 'XEU';

    /** French Gold Franc */
    case frenchgoldfranc = 'XFO';

    /** French UIC-Franc */
    case frenchuicfranc = 'XFU';

    /** West African CFA Franc */
    case westafricancfafranc = 'XOF';

    /** CFP Franc */
    case cfpfranc = 'XPF';

    /** RINET Funds */
    case rinetfunds = 'XRE';

    /** Yemeni Dinar */
    case yemenidinar = 'YDD';

    /** Yemeni Rial */
    case yemenirial = 'YER';

    /** Yugoslavian Hard Dinar (1966–1990) */
    case yugoslavianharddinar19661990 = 'YUD';

    /** Yugoslavian New Dinar (1994–2002) */
    case yugoslaviannewdinar19942002 = 'YUM';

    /** Yugoslavian Convertible Dinar (1990–1992) */
    case yugoslavianconvertibledinar19901992 = 'YUN';

    /** Yugoslavian Reformed Dinar (1992–1993) */
    case yugoslavianreformeddinar19921993 = 'YUR';

    /** South African Rand (financial) */
    case southafricanrandfinancial = 'ZAL';

    /** South African Rand */
    case southafricanrand = 'ZAR';

    /** Zambian Kwacha (1968–2012) */
    case zambiankwacha19682012 = 'ZMK';

    /** Zambian Kwacha */
    case zambiankwacha = 'ZMW';

    /** Zairean New Zaire (1993–1998) */
    case zaireannewzaire19931998 = 'ZRN';

    /** Zairean Zaire (1971–1993) */
    case zaireanzaire19711993 = 'ZRZ';

    /** Zimbabwean Dollar (1980–2008) */
    case zimbabweandollar19802008 = 'ZWD';

    /** Zimbabwean Gold */
    case zimbabweangold = 'ZWG';

    /** Zimbabwean Dollar (2009–2024) */
    case zimbabweandollar20092024 = 'ZWL';

    /** Zimbabwean Dollar (2008) */
    case zimbabweandollar2008 = 'ZWR';
}

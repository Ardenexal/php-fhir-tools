<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SPDXLicense
 * URL: http://hl7.org/fhir/ValueSet/spdx-license
 * Version: 4.0.1
 * Description: The license that applies to an Implementation Guide (using an SPDX license Identifiers, or 'not-open-source'). The binding is required but new SPDX license Identifiers are allowed to be used (https://spdx.org/licenses/).
 */
enum SPDXLicense: string
{
    /** Not open source */
    case notopensource = 'not-open-source';

    /** BSD Zero Clause License */
    case bsdzeroclauselicense = '0BSD';

    /** Attribution Assurance License */
    case attributionassurancelicense = 'AAL';

    /** Abstyles License */
    case abstyleslicense = 'Abstyles';

    /** Adobe Systems Incorporated Source Code License Agreement */
    case adobesystemsincorporatedsourcecodelicenseagreement = 'Adobe-2006';

    /** Adobe Glyph List License */
    case adobeglyphlistlicense = 'Adobe-Glyph';

    /** Amazon Digital Services License */
    case amazondigitalserviceslicense = 'ADSL';

    /** Academic Free License v1.1 */
    case academicfreelicens_ev11 = 'AFL-1.1';

    /** Academic Free License v1.2 */
    case academicfreelicens_ev12 = 'AFL-1.2';

    /** Academic Free License v2.0 */
    case academicfreelicens_ev20 = 'AFL-2.0';

    /** Academic Free License v2.1 */
    case academicfreelicens_ev21 = 'AFL-2.1';

    /** Academic Free License v3.0 */
    case academicfreelicens_ev30 = 'AFL-3.0';

    /** Afmparse License */
    case afmparselicense = 'Afmparse';

    /** Affero General Public License v1.0 only */
    case afferogeneralpubliclicens_ev10_only = 'AGPL-1.0-only';

    /** Affero General Public License v1.0 or later */
    case afferogeneralpubliclicens_ev10_orlater = 'AGPL-1.0-or-later';

    /** GNU Affero General Public License v3.0 only */
    case gnuafferogeneralpubliclicens_ev30_only = 'AGPL-3.0-only';

    /** GNU Affero General Public License v3.0 or later */
    case gnuafferogeneralpubliclicens_ev30_orlater = 'AGPL-3.0-or-later';

    /** Aladdin Free Public License */
    case aladdinfreepubliclicense = 'Aladdin';

    /** AMD's plpa_map.c License */
    case am_ds_plpamapclicense = 'AMDPLPA';

    /** Apple MIT License */
    case applemitlicense = 'AML';

    /** Academy of Motion Picture Arts and Sciences BSD */
    case academyofmotionpictureartsandsciencesbsd = 'AMPAS';

    /** ANTLR Software Rights Notice */
    case antlrsoftwarerightsnotice = 'ANTLR-PD';

    /** Apache License 1.0 */
    case apachelicense10 = 'Apache-1.0';

    /** Apache License 1.1 */
    case apachelicense11 = 'Apache-1.1';

    /** Apache License 2.0 */
    case apachelicense20 = 'Apache-2.0';

    /** Adobe Postscript AFM License */
    case adobepostscriptafmlicense = 'APAFML';

    /** Adaptive Public License 1.0 */
    case adaptivepubliclicense10 = 'APL-1.0';

    /** Apple Public Source License 1.0 */
    case applepublicsourcelicense10 = 'APSL-1.0';

    /** Apple Public Source License 1.1 */
    case applepublicsourcelicense11 = 'APSL-1.1';

    /** Apple Public Source License 1.2 */
    case applepublicsourcelicense12 = 'APSL-1.2';

    /** Apple Public Source License 2.0 */
    case applepublicsourcelicense20 = 'APSL-2.0';

    /** Artistic License 1.0 w/clause 8 */
    case artisticlicense10_wclause8 = 'Artistic-1.0-cl8';

    /** Artistic License 1.0 (Perl) */
    case artisticlicense10_perl = 'Artistic-1.0-Perl';

    /** Artistic License 1.0 */
    case artisticlicense10 = 'Artistic-1.0';

    /** Artistic License 2.0 */
    case artisticlicense20 = 'Artistic-2.0';

    /** Bahyph License */
    case bahyphlicense = 'Bahyph';

    /** Barr License */
    case barrlicense = 'Barr';

    /** Beerware License */
    case beerwarelicense = 'Beerware';

    /** BitTorrent Open Source License v1.0 */
    case bittorrentopensourcelicens_ev10 = 'BitTorrent-1.0';

    /** BitTorrent Open Source License v1.1 */
    case bittorrentopensourcelicens_ev11 = 'BitTorrent-1.1';

    /** Borceux license */
    case borceuxlicense = 'Borceux';

    /** BSD 1-Clause License */
    case bsd1_clauselicense = 'BSD-1-Clause';

    /** BSD 2-Clause FreeBSD License */
    case bsd2_clausefreebsdlicense = 'BSD-2-Clause-FreeBSD';

    /** BSD 2-Clause NetBSD License */
    case bsd2_clausenetbsdlicense = 'BSD-2-Clause-NetBSD';

    /** BSD-2-Clause Plus Patent License */
    case bsd2_clausepluspatentlicense = 'BSD-2-Clause-Patent';

    /** BSD 2-Clause "Simplified" License */
    case bsd2_clausesimplifiedlicense = 'BSD-2-Clause';

    /** BSD with attribution */
    case bsdwithattribution = 'BSD-3-Clause-Attribution';

    /** BSD 3-Clause Clear License */
    case bsd3_clauseclearlicense = 'BSD-3-Clause-Clear';

    /** Lawrence Berkeley National Labs BSD variant license */
    case lawrenceberkeleynationallabsbsdvariantlicense = 'BSD-3-Clause-LBNL';

    /** BSD 3-Clause No Nuclear License 2014 */
    case bsd3_clausenonuclearlicense2014 = 'BSD-3-Clause-No-Nuclear-License-2014';

    /** BSD 3-Clause No Nuclear License */
    case bsd3_clausenonuclearlicense = 'BSD-3-Clause-No-Nuclear-License';

    /** BSD 3-Clause No Nuclear Warranty */
    case bsd3_clausenonuclearwarranty = 'BSD-3-Clause-No-Nuclear-Warranty';

    /** BSD 3-Clause "New" or "Revised" License */
    case bsd3_clauseneworrevisedlicense = 'BSD-3-Clause';

    /** BSD-4-Clause (University of California-Specific) */
    case bsd4_clauseuniversityofcaliforniaspecific = 'BSD-4-Clause-UC';

    /** BSD 4-Clause "Original" or "Old" License */
    case bsd4_clauseoriginaloroldlicense = 'BSD-4-Clause';

    /** BSD Protection License */
    case bsdprotectionlicense = 'BSD-Protection';

    /** BSD Source Code Attribution */
    case bsdsourcecodeattribution = 'BSD-Source-Code';

    /** Boost Software License 1.0 */
    case boostsoftwarelicense10 = 'BSL-1.0';

    /** bzip2 and libbzip2 License v1.0.5 */
    case bzip2_andlibbzip2_licens_ev105 = 'bzip2-1.0.5';

    /** bzip2 and libbzip2 License v1.0.6 */
    case bzip2_andlibbzip2_licens_ev106 = 'bzip2-1.0.6';

    /** Caldera License */
    case calderalicense = 'Caldera';

    /** Computer Associates Trusted Open Source License 1.1 */
    case computerassociatestrustedopensourcelicense11 = 'CATOSL-1.1';

    /** Creative Commons Attribution 1.0 Generic */
    case creativecommonsattribution10_generic = 'CC-BY-1.0';

    /** Creative Commons Attribution 2.0 Generic */
    case creativecommonsattribution20_generic = 'CC-BY-2.0';

    /** Creative Commons Attribution 2.5 Generic */
    case creativecommonsattribution25_generic = 'CC-BY-2.5';

    /** Creative Commons Attribution 3.0 Unported */
    case creativecommonsattribution30_unported = 'CC-BY-3.0';

    /** Creative Commons Attribution 4.0 International */
    case creativecommonsattribution40_international = 'CC-BY-4.0';

    /** Creative Commons Attribution Non Commercial 1.0 Generic */
    case creativecommonsattributionnoncommercial10_generic = 'CC-BY-NC-1.0';

    /** Creative Commons Attribution Non Commercial 2.0 Generic */
    case creativecommonsattributionnoncommercial20_generic = 'CC-BY-NC-2.0';

    /** Creative Commons Attribution Non Commercial 2.5 Generic */
    case creativecommonsattributionnoncommercial25_generic = 'CC-BY-NC-2.5';

    /** Creative Commons Attribution Non Commercial 3.0 Unported */
    case creativecommonsattributionnoncommercial30_unported = 'CC-BY-NC-3.0';

    /** Creative Commons Attribution Non Commercial 4.0 International */
    case creativecommonsattributionnoncommercial40_international = 'CC-BY-NC-4.0';

    /** Creative Commons Attribution Non Commercial No Derivatives 1.0 Generic */
    case creativecommonsattributionnoncommercialnoderivatives10_generic = 'CC-BY-NC-ND-1.0';

    /** Creative Commons Attribution Non Commercial No Derivatives 2.0 Generic */
    case creativecommonsattributionnoncommercialnoderivatives20_generic = 'CC-BY-NC-ND-2.0';

    /** Creative Commons Attribution Non Commercial No Derivatives 2.5 Generic */
    case creativecommonsattributionnoncommercialnoderivatives25_generic = 'CC-BY-NC-ND-2.5';

    /** Creative Commons Attribution Non Commercial No Derivatives 3.0 Unported */
    case creativecommonsattributionnoncommercialnoderivatives30_unported = 'CC-BY-NC-ND-3.0';

    /** Creative Commons Attribution Non Commercial No Derivatives 4.0 International */
    case creativecommonsattributionnoncommercialnoderivatives40_international = 'CC-BY-NC-ND-4.0';

    /** Creative Commons Attribution Non Commercial Share Alike 1.0 Generic */
    case creativecommonsattributionnoncommercialsharealike10_generic = 'CC-BY-NC-SA-1.0';

    /** Creative Commons Attribution Non Commercial Share Alike 2.0 Generic */
    case creativecommonsattributionnoncommercialsharealike20_generic = 'CC-BY-NC-SA-2.0';

    /** Creative Commons Attribution Non Commercial Share Alike 2.5 Generic */
    case creativecommonsattributionnoncommercialsharealike25_generic = 'CC-BY-NC-SA-2.5';

    /** Creative Commons Attribution Non Commercial Share Alike 3.0 Unported */
    case creativecommonsattributionnoncommercialsharealike30_unported = 'CC-BY-NC-SA-3.0';

    /** Creative Commons Attribution Non Commercial Share Alike 4.0 International */
    case creativecommonsattributionnoncommercialsharealike40_international = 'CC-BY-NC-SA-4.0';

    /** Creative Commons Attribution No Derivatives 1.0 Generic */
    case creativecommonsattributionnoderivatives10_generic = 'CC-BY-ND-1.0';

    /** Creative Commons Attribution No Derivatives 2.0 Generic */
    case creativecommonsattributionnoderivatives20_generic = 'CC-BY-ND-2.0';

    /** Creative Commons Attribution No Derivatives 2.5 Generic */
    case creativecommonsattributionnoderivatives25_generic = 'CC-BY-ND-2.5';

    /** Creative Commons Attribution No Derivatives 3.0 Unported */
    case creativecommonsattributionnoderivatives30_unported = 'CC-BY-ND-3.0';

    /** Creative Commons Attribution No Derivatives 4.0 International */
    case creativecommonsattributionnoderivatives40_international = 'CC-BY-ND-4.0';

    /** Creative Commons Attribution Share Alike 1.0 Generic */
    case creativecommonsattributionsharealike10_generic = 'CC-BY-SA-1.0';

    /** Creative Commons Attribution Share Alike 2.0 Generic */
    case creativecommonsattributionsharealike20_generic = 'CC-BY-SA-2.0';

    /** Creative Commons Attribution Share Alike 2.5 Generic */
    case creativecommonsattributionsharealike25_generic = 'CC-BY-SA-2.5';

    /** Creative Commons Attribution Share Alike 3.0 Unported */
    case creativecommonsattributionsharealike30_unported = 'CC-BY-SA-3.0';

    /** Creative Commons Attribution Share Alike 4.0 International */
    case creativecommonsattributionsharealike40_international = 'CC-BY-SA-4.0';

    /** Creative Commons Zero v1.0 Universal */
    case creativecommonszer_ov10_universal = 'CC0-1.0';

    /** Common Development and Distribution License 1.0 */
    case commondevelopmentanddistributionlicense10 = 'CDDL-1.0';

    /** Common Development and Distribution License 1.1 */
    case commondevelopmentanddistributionlicense11 = 'CDDL-1.1';

    /** Community Data License Agreement Permissive 1.0 */
    case communitydatalicenseagreementpermissive10 = 'CDLA-Permissive-1.0';

    /** Community Data License Agreement Sharing 1.0 */
    case communitydatalicenseagreementsharing10 = 'CDLA-Sharing-1.0';

    /** CeCILL Free Software License Agreement v1.0 */
    case cecillfreesoftwarelicenseagreemen_tv10 = 'CECILL-1.0';

    /** CeCILL Free Software License Agreement v1.1 */
    case cecillfreesoftwarelicenseagreemen_tv11 = 'CECILL-1.1';

    /** CeCILL Free Software License Agreement v2.0 */
    case cecillfreesoftwarelicenseagreemen_tv20 = 'CECILL-2.0';

    /** CeCILL Free Software License Agreement v2.1 */
    case cecillfreesoftwarelicenseagreemen_tv21 = 'CECILL-2.1';

    /** CeCILL-B Free Software License Agreement */
    case cecil_lb_freesoftwarelicenseagreement = 'CECILL-B';

    /** CeCILL-C Free Software License Agreement */
    case cecil_lc_freesoftwarelicenseagreement = 'CECILL-C';

    /** Clarified Artistic License */
    case clarifiedartisticlicense = 'ClArtistic';

    /** CNRI Jython License */
    case cnrijythonlicense = 'CNRI-Jython';

    /** CNRI Python Open Source GPL Compatible License Agreement */
    case cnripythonopensourcegplcompatiblelicenseagreement = 'CNRI-Python-GPL-Compatible';

    /** CNRI Python License */
    case cnripythonlicense = 'CNRI-Python';

    /** Condor Public License v1.1 */
    case condorpubliclicens_ev11 = 'Condor-1.1';

    /** Common Public Attribution License 1.0 */
    case commonpublicattributionlicense10 = 'CPAL-1.0';

    /** Common Public License 1.0 */
    case commonpubliclicense10 = 'CPL-1.0';

    /** Code Project Open License 1.02 */
    case codeprojectopenlicense102 = 'CPOL-1.02';

    /** Crossword License */
    case crosswordlicense = 'Crossword';

    /** CrystalStacker License */
    case crystalstackerlicense = 'CrystalStacker';

    /** CUA Office Public License v1.0 */
    case cuaofficepubliclicens_ev10 = 'CUA-OPL-1.0';

    /** Cube License */
    case cubelicense = 'Cube';

    /** curl License */
    case curllicense = 'curl';

    /** Deutsche Freie Software Lizenz */
    case deutschefreiesoftwarelizenz = 'D-FSL-1.0';

    /** diffmark license */
    case diffmarklicense = 'diffmark';

    /** DOC License */
    case doclicense = 'DOC';

    /** Dotseqn License */
    case dotseqnlicense = 'Dotseqn';

    /** DSDP License */
    case dsdplicense = 'DSDP';

    /** dvipdfm License */
    case dvipdfmlicense = 'dvipdfm';

    /** Educational Community License v1.0 */
    case educationalcommunitylicens_ev10 = 'ECL-1.0';

    /** Educational Community License v2.0 */
    case educationalcommunitylicens_ev20 = 'ECL-2.0';

    /** Eiffel Forum License v1.0 */
    case eiffelforumlicens_ev10 = 'EFL-1.0';

    /** Eiffel Forum License v2.0 */
    case eiffelforumlicens_ev20 = 'EFL-2.0';

    /** eGenix.com Public License 1.1.0 */
    case egenixcompubliclicense110 = 'eGenix';

    /** Entessa Public License v1.0 */
    case entessapubliclicens_ev10 = 'Entessa';

    /** Eclipse Public License 1.0 */
    case eclipsepubliclicense10 = 'EPL-1.0';

    /** Eclipse Public License 2.0 */
    case eclipsepubliclicense20 = 'EPL-2.0';

    /** Erlang Public License v1.1 */
    case erlangpubliclicens_ev11 = 'ErlPL-1.1';

    /** EU DataGrid Software License */
    case eudatagridsoftwarelicense = 'EUDatagrid';

    /** European Union Public License 1.0 */
    case europeanunionpubliclicense10 = 'EUPL-1.0';

    /** European Union Public License 1.1 */
    case europeanunionpubliclicense11 = 'EUPL-1.1';

    /** European Union Public License 1.2 */
    case europeanunionpubliclicense12 = 'EUPL-1.2';

    /** Eurosym License */
    case eurosymlicense = 'Eurosym';

    /** Fair License */
    case fairlicense = 'Fair';

    /** Frameworx Open License 1.0 */
    case frameworxopenlicense10 = 'Frameworx-1.0';

    /** FreeImage Public License v1.0 */
    case freeimagepubliclicens_ev10 = 'FreeImage';

    /** FSF All Permissive License */
    case fsfallpermissivelicense = 'FSFAP';

    /** FSF Unlimited License */
    case fsfunlimitedlicense = 'FSFUL';

    /** FSF Unlimited License (with License Retention) */
    case fsfunlimitedlicensewithlicenseretention = 'FSFULLR';

    /** Freetype Project License */
    case freetypeprojectlicense = 'FTL';

    /** GNU Free Documentation License v1.1 only */
    case gnufreedocumentationlicens_ev11_only = 'GFDL-1.1-only';

    /** GNU Free Documentation License v1.1 or later */
    case gnufreedocumentationlicens_ev11_orlater = 'GFDL-1.1-or-later';

    /** GNU Free Documentation License v1.2 only */
    case gnufreedocumentationlicens_ev12_only = 'GFDL-1.2-only';

    /** GNU Free Documentation License v1.2 or later */
    case gnufreedocumentationlicens_ev12_orlater = 'GFDL-1.2-or-later';

    /** GNU Free Documentation License v1.3 only */
    case gnufreedocumentationlicens_ev13_only = 'GFDL-1.3-only';

    /** GNU Free Documentation License v1.3 or later */
    case gnufreedocumentationlicens_ev13_orlater = 'GFDL-1.3-or-later';

    /** Giftware License */
    case giftwarelicense = 'Giftware';

    /** GL2PS License */
    case gl2_pslicense = 'GL2PS';

    /** 3dfx Glide License */
    case CODE_3_dfxglidelicense = 'Glide';

    /** Glulxe License */
    case glulxelicense = 'Glulxe';

    /** gnuplot License */
    case gnuplotlicense = 'gnuplot';

    /** GNU General Public License v1.0 only */
    case gnugeneralpubliclicens_ev10_only = 'GPL-1.0-only';

    /** GNU General Public License v1.0 or later */
    case gnugeneralpubliclicens_ev10_orlater = 'GPL-1.0-or-later';

    /** GNU General Public License v2.0 only */
    case gnugeneralpubliclicens_ev20_only = 'GPL-2.0-only';

    /** GNU General Public License v2.0 or later */
    case gnugeneralpubliclicens_ev20_orlater = 'GPL-2.0-or-later';

    /** GNU General Public License v3.0 only */
    case gnugeneralpubliclicens_ev30_only = 'GPL-3.0-only';

    /** GNU General Public License v3.0 or later */
    case gnugeneralpubliclicens_ev30_orlater = 'GPL-3.0-or-later';

    /** gSOAP Public License v1.3b */
    case gsoappubliclicens_ev13_b = 'gSOAP-1.3b';

    /** Haskell Language Report License */
    case haskelllanguagereportlicense = 'HaskellReport';

    /** Historical Permission Notice and Disclaimer */
    case historicalpermissionnoticeanddisclaimer = 'HPND';

    /** IBM PowerPC Initialization and Boot Software */
    case ibmpowerpcinitializationandbootsoftware = 'IBM-pibs';

    /** ICU License */
    case iculicense = 'ICU';

    /** Independent JPEG Group License */
    case independentjpeggrouplicense = 'IJG';

    /** ImageMagick License */
    case imagemagicklicense = 'ImageMagick';

    /** iMatix Standard Function Library Agreement */
    case imatixstandardfunctionlibraryagreement = 'iMatix';

    /** Imlib2 License */
    case imlib2_license = 'Imlib2';

    /** Info-ZIP License */
    case infoziplicense = 'Info-ZIP';

    /** Intel ACPI Software License Agreement */
    case intelacpisoftwarelicenseagreement = 'Intel-ACPI';

    /** Intel Open Source License */
    case intelopensourcelicense = 'Intel';

    /** Interbase Public License v1.0 */
    case interbasepubliclicens_ev10 = 'Interbase-1.0';

    /** IPA Font License */
    case ipafontlicense = 'IPA';

    /** IBM Public License v1.0 */
    case ibmpubliclicens_ev10 = 'IPL-1.0';

    /** ISC License */
    case isclicense = 'ISC';

    /** JasPer License */
    case jasperlicense = 'JasPer-2.0';

    /** JSON License */
    case jsonlicense = 'JSON';

    /** Licence Art Libre 1.2 */
    case licenceartlibre12 = 'LAL-1.2';

    /** Licence Art Libre 1.3 */
    case licenceartlibre13 = 'LAL-1.3';

    /** Latex2e License */
    case latex2_elicense = 'Latex2e';

    /** Leptonica License */
    case leptonicalicense = 'Leptonica';

    /** GNU Library General Public License v2 only */
    case gnulibrarygeneralpubliclicens_ev2_only = 'LGPL-2.0-only';

    /** GNU Library General Public License v2 or later */
    case gnulibrarygeneralpubliclicens_ev2_orlater = 'LGPL-2.0-or-later';

    /** GNU Lesser General Public License v2.1 only */
    case gnulessergeneralpubliclicens_ev21_only = 'LGPL-2.1-only';

    /** GNU Lesser General Public License v2.1 or later */
    case gnulessergeneralpubliclicens_ev21_orlater = 'LGPL-2.1-or-later';

    /** GNU Lesser General Public License v3.0 only */
    case gnulessergeneralpubliclicens_ev30_only = 'LGPL-3.0-only';

    /** GNU Lesser General Public License v3.0 or later */
    case gnulessergeneralpubliclicens_ev30_orlater = 'LGPL-3.0-or-later';

    /** Lesser General Public License For Linguistic Resources */
    case lessergeneralpubliclicenseforlinguisticresources = 'LGPLLR';

    /** libpng License */
    case libpnglicense = 'Libpng';

    /** libtiff License */
    case libtifflicense = 'libtiff';

    /** Licence Libre du Québec – Permissive version 1.1 */
    case licencelibreduquebecpermissiveversion11 = 'LiLiQ-P-1.1';

    /** Licence Libre du Québec – Réciprocité version 1.1 */
    case licencelibreduquebecreciprociteversion11 = 'LiLiQ-R-1.1';

    /** Licence Libre du Québec – Réciprocité forte version 1.1 */
    case licencelibreduquebecreciprociteforteversion11 = 'LiLiQ-Rplus-1.1';

    /** Linux Kernel Variant of OpenIB.org license */
    case linuxkernelvariantofopeniborglicense = 'Linux-OpenIB';

    /** Lucent Public License Version 1.0 */
    case lucentpubliclicenseversion10 = 'LPL-1.0';

    /** Lucent Public License v1.02 */
    case lucentpubliclicens_ev102 = 'LPL-1.02';

    /** LaTeX Project Public License v1.0 */
    case latexprojectpubliclicens_ev10 = 'LPPL-1.0';

    /** LaTeX Project Public License v1.1 */
    case latexprojectpubliclicens_ev11 = 'LPPL-1.1';

    /** LaTeX Project Public License v1.2 */
    case latexprojectpubliclicens_ev12 = 'LPPL-1.2';

    /** LaTeX Project Public License v1.3a */
    case latexprojectpubliclicens_ev13_a = 'LPPL-1.3a';

    /** LaTeX Project Public License v1.3c */
    case latexprojectpubliclicens_ev13_c = 'LPPL-1.3c';

    /** MakeIndex License */
    case makeindexlicense = 'MakeIndex';

    /** MirOS License */
    case miroslicense = 'MirOS';

    /** MIT No Attribution */
    case mitnoattribution = 'MIT-0';

    /** Enlightenment License (e16) */
    case enlightenmentlicens_ee16 = 'MIT-advertising';

    /** CMU License */
    case cmulicense = 'MIT-CMU';

    /** enna License */
    case ennalicense = 'MIT-enna';

    /** feh License */
    case fehlicense = 'MIT-feh';

    /** MIT License */
    case mitlicense = 'MIT';

    /** MIT +no-false-attribs license */
    case mitnofalseattribslicense = 'MITNFA';

    /** Motosoto License */
    case motosotolicense = 'Motosoto';

    /** mpich2 License */
    case mpich2_license = 'mpich2';

    /** Mozilla Public License 1.0 */
    case mozillapubliclicense10 = 'MPL-1.0';

    /** Mozilla Public License 1.1 */
    case mozillapubliclicense11 = 'MPL-1.1';

    /** Mozilla Public License 2.0 (no copyleft exception) */
    case mozillapubliclicense20_nocopyleftexception = 'MPL-2.0-no-copyleft-exception';

    /** Mozilla Public License 2.0 */
    case mozillapubliclicense20 = 'MPL-2.0';

    /** Microsoft Public License */
    case microsoftpubliclicense = 'MS-PL';

    /** Microsoft Reciprocal License */
    case microsoftreciprocallicense = 'MS-RL';

    /** Matrix Template Library License */
    case matrixtemplatelibrarylicense = 'MTLL';

    /** Multics License */
    case multicslicense = 'Multics';

    /** Mup License */
    case muplicense = 'Mup';

    /** NASA Open Source Agreement 1.3 */
    case nasaopensourceagreement13 = 'NASA-1.3';

    /** Naumen Public License */
    case naumenpubliclicense = 'Naumen';

    /** Net Boolean Public License v1 */
    case netbooleanpubliclicens_ev1 = 'NBPL-1.0';

    /** University of Illinois/NCSA Open Source License */
    case universityofillinoisncsaopensourcelicense = 'NCSA';

    /** Net-SNMP License */
    case netsnmplicense = 'Net-SNMP';

    /** NetCDF license */
    case netcdflicense = 'NetCDF';

    /** Newsletr License */
    case newsletrlicense = 'Newsletr';

    /** Nethack General Public License */
    case nethackgeneralpubliclicense = 'NGPL';

    /** Norwegian Licence for Open Government Data */
    case norwegianlicenceforopengovernmentdata = 'NLOD-1.0';

    /** No Limit Public License */
    case nolimitpubliclicense = 'NLPL';

    /** Nokia Open Source License */
    case nokiaopensourcelicense = 'Nokia';

    /** Netizen Open Source License */
    case netizenopensourcelicense = 'NOSL';

    /** Noweb License */
    case noweblicense = 'Noweb';

    /** Netscape Public License v1.0 */
    case netscapepubliclicens_ev10 = 'NPL-1.0';

    /** Netscape Public License v1.1 */
    case netscapepubliclicens_ev11 = 'NPL-1.1';

    /** Non-Profit Open Software License 3.0 */
    case nonprofitopensoftwarelicense30 = 'NPOSL-3.0';

    /** NRL License */
    case nrllicense = 'NRL';

    /** NTP License */
    case ntplicense = 'NTP';

    /** Open CASCADE Technology Public License */
    case opencascadetechnologypubliclicense = 'OCCT-PL';

    /** OCLC Research Public License 2.0 */
    case oclcresearchpubliclicense20 = 'OCLC-2.0';

    /** ODC Open Database License v1.0 */
    case odcopendatabaselicens_ev10 = 'ODbL-1.0';

    /** SIL Open Font License 1.0 */
    case silopenfontlicense10 = 'OFL-1.0';

    /** SIL Open Font License 1.1 */
    case silopenfontlicense11 = 'OFL-1.1';

    /** Open Group Test Suite License */
    case opengrouptestsuitelicense = 'OGTSL';

    /** Open LDAP Public License v1.1 */
    case openldappubliclicens_ev11 = 'OLDAP-1.1';

    /** Open LDAP Public License v1.2 */
    case openldappubliclicens_ev12 = 'OLDAP-1.2';

    /** Open LDAP Public License v1.3 */
    case openldappubliclicens_ev13 = 'OLDAP-1.3';

    /** Open LDAP Public License v1.4 */
    case openldappubliclicens_ev14 = 'OLDAP-1.4';

    /** Open LDAP Public License v2.0.1 */
    case openldappubliclicens_ev201 = 'OLDAP-2.0.1';

    /** Open LDAP Public License v2.0 (or possibly 2.0A and 2.0B) */
    case openldappubliclicens_ev20_orpossibly20_aand20_b = 'OLDAP-2.0';

    /** Open LDAP Public License v2.1 */
    case openldappubliclicens_ev21 = 'OLDAP-2.1';

    /** Open LDAP Public License v2.2.1 */
    case openldappubliclicens_ev221 = 'OLDAP-2.2.1';

    /** Open LDAP Public License 2.2.2 */
    case openldappubliclicense222 = 'OLDAP-2.2.2';

    /** Open LDAP Public License v2.2 */
    case openldappubliclicens_ev22 = 'OLDAP-2.2';

    /** Open LDAP Public License v2.3 */
    case openldappubliclicens_ev23 = 'OLDAP-2.3';

    /** Open LDAP Public License v2.4 */
    case openldappubliclicens_ev24 = 'OLDAP-2.4';

    /** Open LDAP Public License v2.5 */
    case openldappubliclicens_ev25 = 'OLDAP-2.5';

    /** Open LDAP Public License v2.6 */
    case openldappubliclicens_ev26 = 'OLDAP-2.6';

    /** Open LDAP Public License v2.7 */
    case openldappubliclicens_ev27 = 'OLDAP-2.7';

    /** Open LDAP Public License v2.8 */
    case openldappubliclicens_ev28 = 'OLDAP-2.8';

    /** Open Market License */
    case openmarketlicense = 'OML';

    /** OpenSSL License */
    case openssllicense = 'OpenSSL';

    /** Open Public License v1.0 */
    case openpubliclicens_ev10 = 'OPL-1.0';

    /** OSET Public License version 2.1 */
    case osetpubliclicenseversion21 = 'OSET-PL-2.1';

    /** Open Software License 1.0 */
    case opensoftwarelicense10 = 'OSL-1.0';

    /** Open Software License 1.1 */
    case opensoftwarelicense11 = 'OSL-1.1';

    /** Open Software License 2.0 */
    case opensoftwarelicense20 = 'OSL-2.0';

    /** Open Software License 2.1 */
    case opensoftwarelicense21 = 'OSL-2.1';

    /** Open Software License 3.0 */
    case opensoftwarelicense30 = 'OSL-3.0';

    /** ODC Public Domain Dedication & License 1.0 */
    case odcpublicdomaindedicationandlicense10 = 'PDDL-1.0';

    /** PHP License v3.0 */
    case phplicens_ev30 = 'PHP-3.0';

    /** PHP License v3.01 */
    case phplicens_ev301 = 'PHP-3.01';

    /** Plexus Classworlds License */
    case plexusclassworldslicense = 'Plexus';

    /** PostgreSQL License */
    case postgresqllicense = 'PostgreSQL';

    /** psfrag License */
    case psfraglicense = 'psfrag';

    /** psutils License */
    case psutilslicense = 'psutils';

    /** Python License 2.0 */
    case pythonlicense20 = 'Python-2.0';

    /** Qhull License */
    case qhulllicense = 'Qhull';

    /** Q Public License 1.0 */
    case q_publiclicense10 = 'QPL-1.0';

    /** Rdisc License */
    case rdisclicense = 'Rdisc';

    /** Red Hat eCos Public License v1.1 */
    case redhatecospubliclicens_ev11 = 'RHeCos-1.1';

    /** Reciprocal Public License 1.1 */
    case reciprocalpubliclicense11 = 'RPL-1.1';

    /** Reciprocal Public License 1.5 */
    case reciprocalpubliclicense15 = 'RPL-1.5';

    /** RealNetworks Public Source License v1.0 */
    case realnetworkspublicsourcelicens_ev10 = 'RPSL-1.0';

    /** RSA Message-Digest License */
    case rsamessagedigestlicense = 'RSA-MD';

    /** Ricoh Source Code Public License */
    case ricohsourcecodepubliclicense = 'RSCPL';

    /** Ruby License */
    case rubylicense = 'Ruby';

    /** Sax Public Domain Notice */
    case saxpublicdomainnotice = 'SAX-PD';

    /** Saxpath License */
    case saxpathlicense = 'Saxpath';

    /** SCEA Shared Source License */
    case sceasharedsourcelicense = 'SCEA';

    /** Sendmail License */
    case sendmaillicense = 'Sendmail';

    /** SGI Free Software License B v1.0 */
    case sgifreesoftwarelicens_eb_v10 = 'SGI-B-1.0';

    /** SGI Free Software License B v1.1 */
    case sgifreesoftwarelicens_eb_v11 = 'SGI-B-1.1';

    /** SGI Free Software License B v2.0 */
    case sgifreesoftwarelicens_eb_v20 = 'SGI-B-2.0';

    /** Simple Public License 2.0 */
    case simplepubliclicense20 = 'SimPL-2.0';

    /** Sun Industry Standards Source License v1.2 */
    case sunindustrystandardssourcelicens_ev12 = 'SISSL-1.2';

    /** Sun Industry Standards Source License v1.1 */
    case sunindustrystandardssourcelicens_ev11 = 'SISSL';

    /** Sleepycat License */
    case sleepycatlicense = 'Sleepycat';

    /** Standard ML of New Jersey License */
    case standardmlofnewjerseylicense = 'SMLNJ';

    /** Secure Messaging Protocol Public License */
    case securemessagingprotocolpubliclicense = 'SMPPL';

    /** SNIA Public License 1.1 */
    case sniapubliclicense11 = 'SNIA';

    /** Spencer License 86 */
    case spencerlicense86 = 'Spencer-86';

    /** Spencer License 94 */
    case spencerlicense94 = 'Spencer-94';

    /** Spencer License 99 */
    case spencerlicense99 = 'Spencer-99';

    /** Sun Public License v1.0 */
    case sunpubliclicens_ev10 = 'SPL-1.0';

    /** SugarCRM Public License v1.1.3 */
    case sugarcrmpubliclicens_ev113 = 'SugarCRM-1.1.3';

    /** Scheme Widget Library (SWL) Software License Agreement */
    case schemewidgetlibraryswlsoftwarelicenseagreement = 'SWL';

    /** TCL/TK License */
    case tcltklicense = 'TCL';

    /** TCP Wrappers License */
    case tcpwrapperslicense = 'TCP-wrappers';

    /** TMate Open Source License */
    case tmateopensourcelicense = 'TMate';

    /** TORQUE v2.5+ Software License v1.1 */
    case torqu_ev25_softwarelicensev11 = 'TORQUE-1.1';

    /** Trusster Open Source License */
    case trussteropensourcelicense = 'TOSL';

    /** Unicode License Agreement - Data Files and Software (2015) */
    case unicodelicenseagreementdatafilesandsoftware2015 = 'Unicode-DFS-2015';

    /** Unicode License Agreement - Data Files and Software (2016) */
    case unicodelicenseagreementdatafilesandsoftware2016 = 'Unicode-DFS-2016';

    /** Unicode Terms of Use */
    case unicodetermsofuse = 'Unicode-TOU';

    /** The Unlicense */
    case theunlicense = 'Unlicense';

    /** Universal Permissive License v1.0 */
    case universalpermissivelicens_ev10 = 'UPL-1.0';

    /** Vim License */
    case vimlicense = 'Vim';

    /** VOSTROM Public License for Open Source */
    case vostrompubliclicenseforopensource = 'VOSTROM';

    /** Vovida Software License v1.0 */
    case vovidasoftwarelicens_ev10 = 'VSL-1.0';

    /** W3C Software Notice and License (1998-07-20) */
    case w3_csoftwarenoticeandlicense19980720 = 'W3C-19980720';

    /** W3C Software Notice and Document License (2015-05-13) */
    case w3_csoftwarenoticeanddocumentlicense20150513 = 'W3C-20150513';

    /** W3C Software Notice and License (2002-12-31) */
    case w3_csoftwarenoticeandlicense20021231 = 'W3C';

    /** Sybase Open Watcom Public License 1.0 */
    case sybaseopenwatcompubliclicense10 = 'Watcom-1.0';

    /** Wsuipa License */
    case wsuipalicense = 'Wsuipa';

    /** Do What The F*ck You Want To Public License */
    case dowhatth_ef_ckyouwanttopubliclicense = 'WTFPL';

    /** X11 License */
    case x11_license = 'X11';

    /** Xerox License */
    case xeroxlicense = 'Xerox';

    /** XFree86 License 1.1 */
    case xfree86_license11 = 'XFree86-1.1';

    /** xinetd License */
    case xinetdlicense = 'xinetd';

    /** X.Net License */
    case x_netlicense = 'Xnet';

    /** XPP License */
    case xpplicense = 'xpp';

    /** XSkat License */
    case xskatlicense = 'XSkat';

    /** Yahoo! Public License v1.0 */
    case yahoopubliclicens_ev10 = 'YPL-1.0';

    /** Yahoo! Public License v1.1 */
    case yahoopubliclicens_ev11 = 'YPL-1.1';

    /** Zed License */
    case zedlicense = 'Zed';

    /** Zend License v2.0 */
    case zendlicens_ev20 = 'Zend-2.0';

    /** Zimbra Public License v1.3 */
    case zimbrapubliclicens_ev13 = 'Zimbra-1.3';

    /** Zimbra Public License v1.4 */
    case zimbrapubliclicens_ev14 = 'Zimbra-1.4';

    /** zlib/libpng License with Acknowledgement */
    case zliblibpnglicensewithacknowledgement = 'zlib-acknowledgement';

    /** zlib License */
    case zliblicense = 'Zlib';

    /** Zope Public License 1.1 */
    case zopepubliclicense11 = 'ZPL-1.1';

    /** Zope Public License 2.0 */
    case zopepubliclicense20 = 'ZPL-2.0';

    /** Zope Public License 2.1 */
    case zopepubliclicense21 = 'ZPL-2.1';
}

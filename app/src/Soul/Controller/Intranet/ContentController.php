<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller\Intranet;


/**
 * Class ContentController
 * i
 * @package Soul\Controller
 */
class ContentController extends \Soul\Controller\Base
{
    /**
     * @param $name
     *
     */
    public function showAction($name)
    {
        $this->view->pick('content/'.$name);
    }

    public function generateCodFix()
    {
        $keys = [
            "DQ8G SWEJ PEDQ SPPG 7162",
            "MDSL D4TU QJJP UG8Y 7F05",
            "MPDT UUEE TTEW PEQ4 5EB9",
            "QPUW YDSJ GUPY DSTY 4759",
            "TD8W LQMU SS8G MDUP 0232",
            "QPPU 4GPY WG8M TE8D C313",
            "USST JEET MGP4 SGPY AAE2",
            "DLUY S8LS JTEP LPJW 7AF3",
            "L2J4 2W8G DTYT J4MU B039",
            "YUTJ 2TS8 TL8E J2Y4 1E7D",
            "8EGE S4MW QD8Q WGYJ CC66",
            "G2LS U44G D2GY PJG2 64BE",
            "W8PL QEGQ 4QDE 8TTW F471",
            "TL2M MJUP SJLD 2LGP 5F77",
            "4YSW UYLW JSDS USJ8 4D57",
            "UJME EJ2M DM2S 2QES 810E",
            "JPJW WDYU TT4U 4TE8 E167",
            "MUMW PG8Q DDL2 8L42 5E74",
            "MYUS WWMS MUUW W4D2 30F3",
            "4WYL JWDP JTWU P8E2 05B9",
            "D4WQ TM42 YGP4 2LWQ 2EBF",
            "MWYY SPWT QDLG UGTQ 14FB",
            "YGWL MTEQ QPWQ UDTG BC14",
            "TQJJ MUGW S2PQ E4Q8 6958",
            "UTJJ G2EL E8LP SGDP FA51",
            "YEYM STUE L4MD PU8G 2CDA",
            "GDQ8 4S2T G2QT 4DLT A6F8",
            "8TSM 2LYM SDGE 2TJM B0AB",
        ];

        $key = str_replace(" ", "", $k[array_rand($keys)]);

        $fix = <<<EOF
Windows Registry Editor Version 5.00

[HKEY_LOCAL_MACHINE\SOFTWARE\WOW6432Node\Activision]

[HKEY_LOCAL_MACHINE\SOFTWARE\WOW6432Node\Activision\Call of Duty 4]
"codkey"="{$key}"
EOF;

        $response = new Response();
        $response->setContentType('text/csv');
        $response->setHeader("Content-Disposition", "attachment; filename=cod4_reg_fix.reg");
        $response->setHeader("Pragma", "no-cache");
        $response->setHeader("Expires", "0");
        $response->setExpires(new \DateTime('now'));

        $response->setContent($fix);

        return $response->send();
    }
}
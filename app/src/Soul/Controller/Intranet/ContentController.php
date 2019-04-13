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
    private function getCod4Key()
    {
        $keys = [
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

        return str_replace(" ", "" , array_rand($keys));
    }

    /**
     * @param $name
     *
     */
    public function showAction($name)
    {
        $this->view->pick('content/'.$name);
    }

    public function downloadCodFixAction()
    {

        $output = '
Windows Registry Editor Version 5.00

[HKEY_LOCAL_MACHINE\SOFTWARE\WOW6432Node\Activision]

[HKEY_LOCAL_MACHINE\SOFTWARE\WOW6432Node\Activision\Call of Duty 4]
"codkey"="'.$this->getCod4Key().'"';

        $response = new Response();
        $response->setContentType('text/csv');
        $response->setHeader("Content-Disposition", "attachment; filename=cod4_key_fix.reg");
        $response->setHeader("Pragma", "no-cache");
        $response->setHeader("Expires", "0");
        $response->setExpires(new \DateTime('now'));

        $response->setContent($output);

    }
}
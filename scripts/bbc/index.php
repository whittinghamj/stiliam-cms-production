<?php
// env
error_reporting(0);
set_time_limit(0);
date_default_timezone_set( "Europe/London" );

/* ////////////////////////////// start config ////////////////////////////// */
$ip_protection          = false; // true or false. Make sure you add all you IP addresses if protection is enabled.
$allowed_ips            = array(
                            '8.8.8.8.', 
                            '1.1.1.1', 
                            '4.2.2.1', 
                        );

/* ////////////////////////////// end config ////////////////////////////// */

// vars
$id                     = get( 'id' );
$client_ip              = $_SERVER['REMOTE_ADDR'];

// check ip protection
if( $ip_protection == true ) {
    // check if the REMOTE_ADDR is allowed or not
    if( !in_array( $client_ip, $allowed_ips ) ) {
        header('HTTP/1.0 403 Forbidden');
        die();
    }
}

// get current url
if( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ) {
    $link = "https";
} else {
    $link = "http";
}
$link .= "://";
$link .= $_SERVER['HTTP_HOST'];
$link .= $_SERVER['REQUEST_URI'];

// functions
function search_multi_array( $dataArray, $search_value, $key_to_search ) {
    $keys = array();
    foreach( $dataArray as $key => $cur_value ) {
        if( $cur_value[$key_to_search] == $search_value ) {
            $keys[] = $key;
        }
    }
    return $keys;
}

function get( $key = null ) {
    if( is_null( $key ) ) {
        return $_GET;
    }
    $get = isset( $_GET[$key] ) ? $_GET[$key] : null;
    if ( is_string( $get ) ) {
        $get = trim( $get );
    }
    // $get = addslashes($get);
    return $get;
}

// datasets
$channels[0]['id']      = 'bbc_one_hd';
$channels[0]['name']    = 'BBC One HD';
$channels[0]['source']  = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/abr_hdtv/ak/bbc_one_hd.m3u8';

$channels[1]['id']      = 'bbc_one_london';
$channels[1]['name']    = 'BBC One London';
$channels[1]['source']  = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_london.m3u8';

$channels[2]['id']      = 'bbc_one_northern_ireland_hd';
$channels[2]['name']    = 'BBC One Northern Ireland HD';
$channels[2]['source']  = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/abr_hdtv/ak/bbc_one_northern_ireland_hd.m3u8';

$channels[13]['id']      = 'bbc_one_scotland_hd';
$channels[13]['name']    = 'BBC One Scotland HD';
$channels[13]['source']  = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/abr_hdtv/ak/bbc_one_scotland_hd.m3u8';

$channels[4]['id']      = 'bbc_two_hd';
$channels[4]['name']    = 'BBC Two HD';
$channels[4]['source']  = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/abr_hdtv/ak/bbc_two_england.m3u8';

$channels[5]['id']      = 'bbc_two_wales';
$channels[5]['name']    = 'BBC Two Wales';
$channels[5]['source']  = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_two_wales_digital.m3u8';

$channels[6]['id']      = 'bbc_two_northern_ireland_hd';
$channels[6]['name']    = 'BBC Two Northern Ireland_hd';
$channels[6]['source']  = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_two_northern_ireland_digital.m3u8';

$channels[7]['id']      = 'bbc_two_scotland';
$channels[7]['name']    = 'BBC Two Scotland';
$channels[7]['source']  = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_two_scotland.m3u8';

$channels[8]['id']      = 'bbc_alba';
$channels[8]['name']    = 'BBC ALBA';
$channels[8]['source']  = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_alba.m3u8';

$channels[9]['id']      = 'bbc_four_hd';
$channels[9]['name']    = 'BBC Four HD';
$channels[9]['source']  = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/abr_hdtv/ak/bbc_four.m3u8';

$channels[10]['id']     = 'bbc_news_hd';
$channels[10]['name']   = 'BBC News HD';
$channels[10]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/abr_hdtv/ak/bbc_news24.m3u8';

$channels[11]['id']     = 'bbc_one_cambridge';
$channels[11]['name']   = 'BBC One Cambridge';
$channels[11]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_cambridge.m3u8';

$channels[12]['id']     = 'bbc_one_channel_islands';
$channels[12]['name']   = 'BBC One Channel Islands';
$channels[12]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_channel_islands.m3u8';

$channels[13]['id']     = 'bbc_one_east';
$channels[13]['name']   = 'BBC One East';
$channels[13]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_east.m3u8';

$channels[14]['id']     = 'bbc_one_east_midlands';
$channels[14]['name']   = 'BBC One East Midlands';
$channels[14]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_east_midlands.m3u8';

$channels[15]['id']     = 'bbc_one_east_yorkshire';
$channels[15]['name']   = 'BBC One East Yorkshire';
$channels[15]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_east_yorkshire.m3u8';

$channels[15]['id']     = 'bbc_one_east_yorkshire';
$channels[15]['name']   = 'BBC One East Yorkshire';
$channels[15]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_east_yorkshire.m3u8';

$channels[16]['id']     = 'bbc_news_arabic';
$channels[16]['name']   = 'BBC News Arabic';
$channels[16]['source'] = 'https://vs-hls-ww.live.cf.md.bbci.co.uk/pool_902/live/nonuk/bbc_arabic_tv/bbc_arabic_tv.isml/index.m3u8';

$channels[17]['id']     = 'bbc_one_north_east';
$channels[17]['name']   = 'BBC One North East';
$channels[17]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_north_east.m3u8';

$channels[18]['id']     = 'bbc_one_north_west';
$channels[18]['name']   = 'BBC One North West';
$channels[18]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_north_west.m3u8';

$channels[19]['id']     = 'bbc_one_oxford';
$channels[19]['name']   = 'BBC One Oxford';
$channels[19]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_oxford.m3u8';

$channels[20]['id']     = 'bbc_one_south';
$channels[20]['name']   = 'BBC One South';
$channels[20]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_south.m3u8';

$channels[21]['id']     = 'bbc_onebbc_one_south_easthd';
$channels[21]['name']   = 'BBC One South East';
$channels[21]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_south.m3u8';

$channels[22]['id']     = 'bbc_one_south_west';
$channels[22]['name']   = 'BBC One South West';
$channels[22]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_south_west.m3u8';

$channels[23]['id']     = 'bbc_one_west';
$channels[23]['name']   = 'BBC One West';
$channels[23]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_west.m3u8';

$channels[24]['id']     = 'bbc_one_yorks';
$channels[24]['name']   = 'BBC One Yorks';
$channels[24]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_one_yorks.m3u8';

$channels[25]['id']     = 'bbc_parliament';
$channels[25]['name']   = 'BBC Parliament';
$channels[25]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/bbc_parliament.m3u8';

$channels[26]['id']     = 'cbbc_hd';
$channels[26]['name']   = 'CBBC HD';
$channels[26]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/abr_hdtv/ak/cbbc.m3u8';

$channels[27]['id']     = 'cbeebies_hd';
$channels[27]['name']   = 'CBeebiese HD';
$channels[27]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/abr_hdtv/ak/cbeebies.m3u8';

$channels[28]['id']     = 's4c';
$channels[28]['name']   = 'S4C';
$channels[28]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio_video/simulcast/hls/uk/hls_tablet/ak/s4cpbs.m3u8';

$channels[29]['id']     = 'bbc_persian_tv';
$channels[29]['name']   = 'BBC Persian';
$channels[29]['source'] = 'https://vs-hls-ww.live.cf.md.bbci.co.uk/pool_902/live/nonuk/bbc_persian_tv/bbc_persian_tv.isml/index.m3u8';





$channels[129]['id']     = 'bbc_radio_1';
$channels[129]['name']   = 'BBC Radio 1';
$channels[129]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_radio_one.m3u8';

$channels[130]['id']     = 'bbc_radio_1_xtra';
$channels[130]['name']   = 'BBC Radio 1 Xtra';
$channels[130]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_1xtra.m3u8';

$channels[131]['id']     = 'bbc_radio_2';
$channels[131]['name']   = 'BBC Radio 2';
$channels[131]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_radio_two.m3u8';

$channels[132]['id']     = 'bbc_radio_3';
$channels[132]['name']   = 'BBC Radio 3';
$channels[132]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_radio_three.m3u8';

$channels[133]['id']     = 'bbc_radio_4';
$channels[133]['name']   = 'BBC Radio 4';
$channels[133]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_radio_fourfm.m3u8';

$channels[134]['id']     = 'bbc_radio_4';
$channels[134]['name']   = 'BBC Radio 4LW';
$channels[134]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_radio_fourlw.m3u8';

$channels[135]['id']     = 'bbc_radio_4_extra';
$channels[135]['name']   = 'BBC Radio 4 Extra';
$channels[135]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_radio_four_extra.m3u8';

$channels[136]['id']     = 'bbc_radio_5_live';
$channels[136]['name']   = 'BBC radio 5 Live';
$channels[136]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_radio_five_live.m3u8';

$channels[137]['id']     = 'bbc_radio_5_live_sport_extra';
$channels[137]['name']   = 'BBC Radio 5 Live Sport Extra';
$channels[137]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_radio_five_live_sports_extra.m3u8';

$channels[138]['id']     = 'bbc_radio_6_music';
$channels[138]['name']   = 'BBC Radio 6 Music';
$channels[138]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_6music.m3u8';

$channels[139]['id']     = 'bbc_asian_network';
$channels[139]['name']   = 'BBC Asian Network';
$channels[139]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/ak/bbc_asian_network.m3u8';

$channels[140]['id']     = 'bbc_radio_cw';
$channels[140]['name']   = 'BBC Radio Coventry &amp; Warwickshire';
$channels[140]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/llnw/bbc_radio_coventry_warwickshire.m3u8';

$channels[141]['id']     = 'bbc_radio_essex';
$channels[141]['name']   = 'BBC Radio Essex';
$channels[141]['source'] = 'http://a.files.bbci.co.uk/media/live/manifesto/audio/simulcast/hls/uk/sbr_high/llnw/bbc_radio_essex.m3u8';



// sanity check
if( isset( $id ) || !empty( $id ) ) {
    // find the channel by the id given
    $channel_key = search_multi_array( $channels, $id, 'id' );

    // debug
    // echo '<pre>';
    // print_r( $channel_key );
    // echo '</pre>';

    // $channel = $channels[$find_channel];
    // sanity check
    if( !isset( $channel_key ) || !is_array( $channel_key ) ) {
        die( 'unable to find channel with id = '.$id );
    }

    // headers
    // header( 'Access-Control-Allow-Origin: *' );
    // header( 'Content-type: application/vnd.apple.mpegurl' );
    header( "HTTP/1.1 301 Moved Permanently" ); 
    header( "Location: ".$channels[$channel_key['0']]['source'] );
} else {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" >
        <title>BBC Streaming Script - by delta1372</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" >
        <meta name="description" content="" >
        <meta name="author" content="" >

        <!-- favicon -->
        <link rel="icon" href="img/favicon.ico">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <!-- Bootstrap -->
        <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sticky-footer/" >

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <!-- DataTables -->
        <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <script src="plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    </head>

    <body>
        <!-- Begin page content -->
        <main role="main" class="container">
            <h1 class="mt-5"><img src="img/bbc-logo.gif" width="250px" alt="BBC Streaming Script">Streaming Script</h1>

            <form class="">
                <div class="row table-responsive">
                    <div class="col-12">
                        <p>Locate the stream you wish to access and copy the URL and add it to your player / panel. The stream will start after a few monets. It is highly suggested that you enable the 'IP Protection' feature. This can be done by setting '$ip_protection = true' inside the index.php file. Then add all your IP addresses that will be allowed to use this script.<br><br>
                            <strong>Please Note:</strong> These streams are GeoIP locked to UK residential IP addresses. If you want to access these streams from outside the UK then you will need to setup &amp; configure a Proxy server or a VPN server.</p>
                    </div>

                    <div class="col-12">
                        <table id="table" class="table table-bordered table-striped" width="96%">
                            <thead>
                                <tr>
                                    <th width="250px">Name</th>
                                    <th class="no-sort">URL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $channels as $channel ) {?>
                                    <tr>
                                        <td>
                                            <?php echo $channel['name']; ?>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                class="form-control form-control-sm" 
                                                value="<?php echo $link; ?>?id=<?php echo $channel['id']; ?>" 
                                                onClick="this.setSelectionRange(0, this.value.length)" width="100%" readonly>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12">
                        <center>
                            Written by @delta1372.
                        </center>
                    </div>
                </div>
            </form>
        </main>

        <script>
            $(function () {
                $( '#table' ).DataTable({
                    "order": [[ 0, "asc" ]],
                    "responsive": true,
                    "columnDefs": [{
                        "targets"  : 'no-sort',
                        "orderable": false,
                    }],
                    "language": {
                        "emptyTable": "No data found."
                    },
                    "paging": true,
                    "processing": true,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": false,
                    "lengthMenu": [50, 100, 500, 1000],
                    "pageLength": 50,
                    search: {
                       search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
                    }
                });
            });
        </script>
    </body>
</html>

<?php } ?>
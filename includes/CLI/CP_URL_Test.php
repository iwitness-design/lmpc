<?php


// Make the `cp` command available to WP-CLI
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'cp_url', 'CP_URL_Test' );
}

/**
 * WP CLI cp_url command class
 */
class CP_URL_Test {

	/**
	 * Class constructor
	 */
	public function __construct() {}

	/**
	 * Test Urls
	 *
	 * @param $args
	 */
	public function test_urls( $args ) {
		$file = __DIR__ . "/urls.csv";
		$csv = file_get_contents($file);
		$array = array_map( "str_getcsv", explode( "\n", $csv ) );

		$fp = fopen( __DIR__ . '/invalid_urls.txt', 'a');
		$invalid_url_count = 0;

		WP_CLI::success( "Starting URL Test" );

		foreach( $array as $url ) {
			$url = $url[0];

			if( ! $url ) continue;

			$headers = get_headers($url);
 
			if ( ! $headers ) {
				WP_CLI::log("Unable to get headers for URL: " . $url);
				continue; 
			}

			if( strpos( $headers[0], '404' ) !== false ) {
				fwrite($fp, $url . "\n");
				$invalid_url_count++;
			}
		}

		fclose($fp);

		WP_CLI::log( "Invalid URLs: "  . $invalid_url_count );
	}

	/**
	 * Downloads and sideloads URLs from a .txt file
	 * 
	 * 
	 * [--file_path=<file>]
	 * : The path to the .txt file
	 */
	public function migrate_urls( $args, $assoc_args ) {
		$assoc_args = wp_parse_args( $assoc_args, array(
			'file_path' => ABSPATH
		) );

		if( ! $assoc_args['file_path'] ) {
			WP_CLI::error( "Please provide a file path" );
			return;
		}

		$handle = fopen( $assoc_args['file_path'], 'r' );

		WP_CLI::success( "Starting URL Migration" );

		if( $handle ) {
			while(($line = fgets($handle)) !== false) {
				$url = str_replace( "https://lmpc.org", "https://old.lmpc.org", trim( $line ) );

				$result = $this->download_and_sideload_url( $url );

				if( is_wp_error( $result ) ) {
					WP_CLI::log( "Error downloading and sideloading URL: " . $url );
				}
				else {
					WP_CLI::log( "Successfully downloaded and sideloaded URL: " . $url );
				}
			}
		}
	}

	/**
	 * Downloads and sideloads file, using exact same filename, to the media library
	 * 
	 * @param string $url
	 * 
	 * @return int|\WP_Error
	 */
	protected function download_and_sideload_url( $url ) {
		$tmp = download_url( $url );

		if( is_wp_error( $tmp ) ) {
			return $tmp;
		}

		$file_array = array();
		$file_array['name'] = basename( $url );
		$file_array['tmp_name'] = $tmp;

		$id = media_handle_sideload( $file_array, 0 );

		if( is_wp_error( $id ) ) {
			@unlink( $tmp );
			return $id;
		}

		// Get the upload directory
		preg_match( '/\/wp-content\/uploads(\/\d{4}\/\d{2}\/)/', $url, $matches );

		// Get the old and new paths
		$uploads = wp_upload_dir();
		$old_path = $uploads['basedir'] . $uploads['subdir'] . '/' . $file_array['name'];
		$new_path = $uploads['basedir'] . $matches[1] . $file_array['name'];

		// Make sure the directory exists
		wp_mkdir_p( dirname( $new_path ) );

		// Move the file
		rename( $old_path, $new_path );

		// Update the attachment metadata
		update_attached_file( $id, $new_path );

		WP_CLI::log( "Moving file from " . $old_path . " to " . $new_path );

		WP_CLI::log( "New file URL: " . wp_get_attachment_url( $id ) );

		return $id;
	}
}

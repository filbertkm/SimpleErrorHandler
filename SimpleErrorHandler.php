<?php

class SimpleErrorHandler {

	/**
	 * @param int $errorNumber
	 * @param string $errorMessage
	 * @param string $filename
	 * @param int $lineNumber
	 */
	public static function error_handler( $errorNumber, $errorMessage, $filename, $lineNumber ) {
		$errorHandler = new self();
		$errorHandler->handleError( $errorNumber, $errorMessage, $filename, $lineNumber );
	}

	/**
	 * @param int $errorNumber
	 * @param string $message
	 * @param string $fileName
	 * @param int $lineNumber
	 */
	public function handleError( $errorNumber, $errorMessage, $filename, $lineNumber ) {
		if ( 0 === error_reporting() || !$this->doReportLevel( $errorNumber ) ) {
			return false;
		}

		switch ( $errorNumber ) {
			case E_ERROR:
			case E_USER_ERROR:
				$errorLevel = 'Error';
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$errorLevel = 'Warning';
				break;
			case E_NOTICE:
			case E_USER_NOTICE:
				$errorLevel = 'Notice';
				break;
			default:
				$errorLevel = 'Other';
		}

		if ( $errorLevel !== false ) {
			$this->reportError( $errorLevel, $errorMessage, $filename, $lineNumber );
		}

		return true;
	}

	private function doReportLevel( $errorNumber ) {
		return ( error_reporting() & $errorNumber ) === $errorNumber;
	}

	private function reportError( $errorLevel, $message, $filename, $lineNumber ) {
		$errorLines = array_merge(
			array(
				 $errorLevel . ': ' . $message . ' in ' . $filename . ' on line ' . $lineNumber
			),
			$this->formatBacktrace( debug_backtrace() )
		);

		$error = implode( "\n", $errorLines );

		if ( PHP_SAPI === 'cli' ) {
			echo "$error\n";
		} else {
			echo "<div style='background: #eee; padding: 1em'>$error</div>";
		}
	}

	private function formatBacktrace( array $backtraceLines ) {
		$errorLines = array();

		foreach( $backtraceLines as $key => $line ) {
			$errorLines[] = $this->formatBacktraceLine( $key, $line );
		}

		return $errorLines;
	}

	private function formatBacktraceLine( $key, $line ) {
		$errorLine =  '#' . $key . ': ';

		if ( array_key_exists( 'file', $line ) ) {
			$errorLine .= $line['file'];
		}

		if ( array_key_exists( 'line', $line ) ) {
			$errorLine .= ' (' .  $line['line'] . '): ';
		}

		$errorLine .= $line['function'];

		return $errorLine;
	}

	public static function fatal_handler() {
		$last_error = error_get_last();

		if ( $last_error !== null ) {
			// @todo
		}
	}
}

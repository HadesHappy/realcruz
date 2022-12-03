<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<!-- Invoice styling -->
		<style>
			* {
				font-family: DejaVu Sans, sans-serif !important;
			}
			body {
				text-align: center;
				color: #777;
			}

			body h1 {
				font-weight: 300;
				margin-bottom: 0px;
				padding-bottom: 0px;
				color: #000;
			}

			body h3 {
				font-weight: 300;
				margin-top: 10px;
				margin-bottom: 20px;
				font-style: italic;
				color: #555;
			}

			body a {
				color: #06f;
			}

			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				/* border: 1px solid #ddd;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
				font-size: 14px;
				line-height: 20px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
				border-collapse: collapse;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 30px;
			}

			.invoice-box table tr.heading td {
				background: #555;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
				color: #fff;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item > td {
				border-bottom: 1px solid #ddd;
			}

			.invoice-box table tr.item.last > td {
				border-bottom: none;
			}

			.invoice-box table tr.total > td:nth-child(2) {
				border-top: 2px solid #ddd;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

            .default-app-logo * {
                fill: currentColor!important;
            }

            p {
                margin: 0;
            }
		</style>
	</head>

	<body>
		<div class="invoice-box" style="position: relative;">
			<table>
				<tr class="top">
					<td colspan="2" style="padding-left:0;padding-right:0;">
						<table>
							<tr>
								<td class="title" style="padding-left:0;padding-right:0;">
									@if (\Acelle\Model\Setting::get('site_logo_small'))
                                        <img width="200px" class="logo" src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
                                    @else
                                        <span class="default-app-logo">
                                            <img width="200px" class="logo" src="{{ url('images/logo-pdf.png') }}" alt="">
                                        </span>
                                    @endif      
								</td>

								<td style="padding-left:0;padding-right:0;">
									<p style="font-weight:bold">{{ \Acelle\Model\Setting::get('site_name') }}</p>
									<p>{{ \Acelle\Model\Setting::get('site_description') }}</p>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="2" style="padding-left:0;padding-right:0;">
						<h2>{{ trans('messages.invoice.header') }}</h2>
					</td>
				</tr>

				<tr class="information">
					<td colspan="2" style="padding-left:0;padding-right:0;">
						<table>
							<tr>
								<td style="padding-left:0;padding-right:0;">
									<p style="font-weight:bold">{FIRST_NAME} {LAST_NAME}</p>
									<p>{ADDRESS} {COUNTRY}</p>
									<p>{EMAIL}</p>
									<p>{PHONE}</p>
								</td>

								<td style="padding-right:0">
									<table>
										<tr>
											<td style="padding:0;text-align:right;padding-right:10px;">{{ trans('messages.invoice') }} #:</td>
											<td style="padding:0">{INVOICE_NUMBER}</td>
										</tr>
										<tr>
											<td style="padding:0;text-align:right;padding-right:10px;">{{ trans('messages.created_at') }}:</td>
											<td style="padding:0;width:180px">{CURRENT_DATETIME}</td>
										</tr>
										<tr>
											<td style="padding:0;text-align:right;padding-right:10px;">{{ trans('messages.due_date') }}:</td>
											<td style="padding:0">{INVOICE_DUE_DATE}</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<div style="margin-bottom:100px">{ITEMS}</div>

			<div style="position:absolute;bottom: 0px;margin: auto;width:100%;border-top: solid 1px #ddd;padding-top:5px;left:0;">
				{{ trans('messages.invoice.footer') }}
			</div>
		</div>
	</body>
</html>


<script>   
    // Header widget
    class YagoHeaderWidget extends Widget {
        getHtmlId() {
            return "YagoHeaderWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb">
                        <img src="{{ url('themes/yoga/images/widget-header.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Header</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div builder-element="BlockElement" style="background-color:#f6f6f4;">
                    <div class="block-grid container" style="Margin: 0 auto; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                            <!--[if (mso)|(IE)]>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f6f6f4;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                            <tr class="layout-full-width" style="background-color:transparent">
                                                <![endif]-->
                                                <!--[if (mso)|(IE)]>
                                                <td align="center" width="680" style="background-color:transparent;width:680px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:5px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num12" style=" display: table-cell; vertical-align: top;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                            <!--<![endif]-->
                                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; border-top: 0px solid transparent; height: 0px;" valign="top" width="100%">
                                                                                                <tbody>
                                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                                        <td height="0" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </div>
                    </div>
                </div>
                <div builder-element="BlockElement" style="background-color:transparent;">
                    <div class="block-grid container" style="Margin: 0 auto; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                            <!--[if (mso)|(IE)]>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                            <tr class="layout-full-width" style="background-color:transparent">
                                                <![endif]-->
                                                <!--[if (mso)|(IE)]>
                                                <td align="center" width="680" style="background-color:transparent;width:680px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:5px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num12" style=" display: table-cell; vertical-align: top;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                            <!--<![endif]-->
                                                                            <div align="center" class="img-container center autowidth fullwidth">
                                                                                <!--[if mso]>
                                                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr style="line-height:0px">
                                                                                        <td style="" align="center">
                                                                                            <![endif]--><img builder-element="" align="center" alt="Image" border="0" class="center autowidth fullwidth" src="{{ url('themes/yoga/images/top_rounded.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%;  display: block;" title="Image" width="680">
                                                                                            <!--[if mso]>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </div>
                    </div>
                </div>
                <div builder-element="BlockElement" style="background-color:transparent;">
                    <div class="block-grid two-up container" style="Margin: 0 auto; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                            <!--[if (mso)|(IE)]>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                            <tr class="layout-full-width" style="background-color:transparent">
                                                <![endif]-->
                                                <!--[if (mso)|(IE)]>
                                                <td align="center" width="340" style="background-color:transparent;width:340px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-top:5px; padding-bottom:0px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num6" style="min-width: 320px; max-width: 340px; display: table-cell; vertical-align: top; width: 340px;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:0px;">
                                                                            <!--<![endif]-->
                                                                            <div align="left" class="img-container left fixedwidth" style="padding-right: 0px;padding-left: 10px;">
                                                                                <!--[if mso]>
                                                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr style="line-height:0px">
                                                                                        <td style="padding-right: 0px;padding-left: 10px;" align="left">
                                                                                            <![endif]--><img builder-element="" alt="Image" border="0" class="left fixedwidth" src="{{ url('themes/yoga/images/loto_a.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 93px; display: block;" title="Image" width="93">
                                                                                            <!--[if mso]>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#28404F;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:120%;padding-top:10px;padding-bottom:0px;">
                                                                                            <div style="font-size: 12px; line-height: 14px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #28404F;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 14px; line-height: 50px; margin: 0;"><span style="font-size: 42px;"><span style="line-height: 50px; font-size: 42px;"><strong>ETAN</strong></span><span style="line-height: 50px; font-size: 42px;">BONJO</span></span></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 0px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#FFFFFF;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:120%;padding-top:0px;padding-bottom:10px;">
                                                                                            <div style="font-size: 12px; line-height: 14px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #FFFFFF;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 14px; line-height: 16px; margin: 0;"><strong><span style="font-size: 18px; line-height: 21px;">Certified Yoga Therapist</span></strong></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <div align="left" class="button-container" style="padding-top:10px;padding-bottom:10px;">
                                                                                <!--[if mso]>
                                                                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                                    <tr>
                                                                                        <td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="left">
                                                                                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://www.example.com/" style="height:31.5pt; width:138pt; v-text-anchor:middle;" arcsize="5%" stroke="false" fillcolor="#28404F">
                                                                                                <w:anchorlock/>
                                                                                                <v:textbox inset="0,0,0,0">
                                                                                                    <center style="color:#ffffff; font-family:Arial, sans-serif; font-size:12px">
                                                                                                        <![endif]--><a builder-element="" builder-inline-edit="" href="http://www.example.com/" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #ffffff; background-color: #28404F; border-radius: 2px; -webkit-border-radius: 2px; -moz-border-radius: 2px; width: auto; width: auto; border-top: 1px solid #28404F; border-right: 1px solid #28404F; border-bottom: 1px solid #28404F; border-left: 1px solid #28404F; padding-top: 5px; padding-bottom: 5px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;" target="_blank"><span style="padding-left:40px;padding-right:40px;font-size:12px;display:inline-block;">
                                                                                                        <span style="font-size: 16px; line-height: 32px;"><strong><span style="font-size: 12px; line-height: 24px;">DROP ME A LINE</span></strong></span>
                                                                                                        </span></a>
                                                                                                        <!--[if mso]>
                                                                                                    </center>
                                                                                                </v:textbox>
                                                                                            </v:roundrect>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                                <td align="center" width="340" style="background-color:transparent;width:340px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 5px; padding-left: 5px; padding-top:5px; padding-bottom:0px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num6" style="min-width: 320px; max-width: 340px; display: table-cell; vertical-align: top; width: 340px;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 5px; padding-left: 5px;">
                                                                            <!--<![endif]-->
                                                                            <div align="center" class="img-container center autowidth fullwidth" style="padding-right: 0px;padding-left: 0px;">
                                                                                <!--[if mso]>
                                                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr style="line-height:0px">
                                                                                        <td style="padding-right: 0px;padding-left: 0px;" align="center">
                                                                                            <![endif]--><img builder-element="" align="center" alt="Image" border="0" class="center autowidth fullwidth" src="{{ url('themes/yoga/images/photo_placeholder.gif') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 330px; display: block;" title="Image" width="330">
                                                                                            <!--[if mso]>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </div>
                    </div>
                </div>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // About Me widget
    class YagoAboutMeWidget extends Widget {
        getHtmlId() {
            return "YagoAboutMeWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb">
                        <img src="{{ url('themes/yoga/images/widget-about-me.png') }}" />
                    </div>
                    <div class="desc">
                        <label>About Me</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div builder-element="BlockElement" style="background-color:#28404F;">
                    <div class="block-grid container" style="Margin: 0 auto; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                            <!--[if (mso)|(IE)]>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#28404F;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                            <tr class="layout-full-width" style="background-color:transparent">
                                                <![endif]-->
                                                <!--[if (mso)|(IE)]>
                                                <td align="center" width="680" style="background-color:transparent;width:680px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-top:50px; padding-bottom:50px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num12" style=" display: table-cell; vertical-align: top;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:50px; padding-bottom:50px;">
                                                                            <!--<![endif]-->
                                                                            <div align="left" class="img-container left autowidth" style="padding-right: 10px;padding-left: 10px;">
                                                                                <!--[if mso]>
                                                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr style="line-height:0px">
                                                                                        <td style="padding-right: 10px;padding-left: 10px;" align="left">
                                                                                            <![endif]-->
                                                                                            <div style="font-size:1px;line-height:10px"> </div>
                                                                                            <img builder-element="" alt="Image" border="0" class="left autowidth" src="{{ url('themes/yoga/images/loto_b_min.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 79px; display: block;" title="Image" width="79">
                                                                                            <div style="font-size:1px;line-height:10px"> </div>
                                                                                            <!--[if mso]>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 5px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#2BBBB2;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:120%;padding-top:5px;padding-bottom:0px;">
                                                                                            <div style="font-size: 12px; line-height: 14px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #2BBBB2;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 14px; line-height: 45px; margin: 0;"><span style="font-size: 38px;"><strong>Ab</strong>out me</span></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 0px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#F4F4F4;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:150%;padding-top:0px;padding-bottom:0px;">
                                                                                            <div style="font-size: 12px; line-height: 18px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #F4F4F4;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 14px; line-height: 33px; text-align: left; margin: 0;"><span style="font-size: 22px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 5px; padding-bottom: 15px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#F4F4F4;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:180%;padding-top:5px;padding-bottom:15px;">
                                                                                            <div style="font-size: 12px; line-height: 21px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #F4F4F4;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 14px; line-height: 28px; text-align: left; margin: 0;"><span style="font-size: 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce elit lorem, gravida nec rutrum non, sollicitudin eu justo. Pellentesque interdum auctor leo, ut luctus eros tempor vitae. </span></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <div align="left" class="button-container" style="padding-top:10px;padding-bottom:10px;">
                                                                                <!--[if mso]>
                                                                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                                    <tr>
                                                                                        <td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="left">
                                                                                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://www.example.com/" style="height:33pt; width:155.25pt; v-text-anchor:middle;" arcsize="5%" strokeweight="1.5pt" strokecolor="#2BBBB2" fill="false">
                                                                                                <w:anchorlock/>
                                                                                                <v:textbox inset="0,0,0,0">
                                                                                                    <center style="color:#2BBBB2; font-family:Arial, sans-serif; font-size:16px">
                                                                                                        <![endif]--><a builder-element="" builder-inline-edit="" href="http://www.example.com/" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #2BBBB2; background-color: transparent; border-radius: 2px; -webkit-border-radius: 2px; -moz-border-radius: 2px; width: auto; width: auto; border-top: 2px solid #2BBBB2; border-right: 2px solid #2BBBB2; border-bottom: 2px solid #2BBBB2; border-left: 2px solid #2BBBB2; padding-top: 4px; padding-bottom: 4px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;" target="_blank"><span style="padding-left:40px;padding-right:40px;font-size:16px;display:inline-block;">
                                                                                                        <span style="font-size: 16px; line-height: 32px;">Download my cv</span>
                                                                                                        </span></a>
                                                                                                        <!--[if mso]>
                                                                                                    </center>
                                                                                                </v:textbox>
                                                                                            </v:roundrect>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </div>
                    </div>
                </div>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // Experience Header widget
    class YagoExperienceHeaderWidget extends Widget {
        getHtmlId() {
            return "YagoExperienceHeaderWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb">
                        <img src="{{ url('themes/yoga/images/widget-experience-header.png') }}" />
                    </div>
                    <div class="desc">
                        <label>Experience header</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div builder-element="BlockElement" style="background-color:#f6f6f4;">
                    <div class="block-grid container" style="Margin: 0 auto; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                            <!--[if (mso)|(IE)]>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f6f6f4;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                            <tr class="layout-full-width" style="background-color:transparent">
                                                <![endif]-->
                                                <!--[if (mso)|(IE)]>
                                                <td align="center" width="680" style="background-color:transparent;width:680px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:5px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num12" style=" display: table-cell; vertical-align: top;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                            <!--<![endif]-->
                                                                            <div align="center" class="img-container center autowidth fullwidth" style="padding-right: 0px;padding-left: 0px;">
                                                                                <!--[if mso]>
                                                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr style="line-height:0px">
                                                                                        <td style="padding-right: 0px;padding-left: 0px;" align="center">
                                                                                            <![endif]--><img builder-element="" align="center" alt="Image" border="0" class="center autowidth fullwidth" src="{{ url('themes/yoga/images/bottom_rounded.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%;  display: block;" title="Image" width="680">
                                                                                            <!--[if mso]>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                            <div align="left" class="img-container left autowidth" style="padding-right: 10px;padding-left: 10px;">
                                                                                <!--[if mso]>
                                                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr style="line-height:0px">
                                                                                        <td style="padding-right: 10px;padding-left: 10px;" align="left">
                                                                                            <![endif]--><img builder-element="" alt="Image" border="0" class="left autowidth" src="{{ url('themes/yoga/images/loto_c_min.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 83px; display: block;" title="Image" width="83">
                                                                                            <div style="font-size:1px;line-height:10px"> </div>
                                                                                            <!--[if mso]>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </div>
                    </div>
                </div>
                <div builder-element="BlockElement" style="background-color:#f6f6f4;">
                    <div class="block-grid two-up container" style="Margin: 0 auto; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                            <!--[if (mso)|(IE)]>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f6f6f4;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                            <tr class="layout-full-width" style="background-color:transparent">
                                                <![endif]-->
                                                <!--[if (mso)|(IE)]>
                                                <td align="center" width="340" style="background-color:transparent;width:340px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num6" style="min-width: 320px; max-width: 340px; display: table-cell; vertical-align: top; width: 340px;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                            <!--<![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 5px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#28404F;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:120%;padding-top:5px;padding-bottom:10px;">
                                                                                            <div style="font-size: 12px; line-height: 14px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #28404F;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 14px; line-height: 45px; margin: 0;"><span style="font-size: 38px;"><strong>Ex</strong>perience</span></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                                <td align="center" width="340" style="background-color:transparent;width:340px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:10px; padding-bottom:5px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num6" style="min-width: 320px; max-width: 340px; display: table-cell; vertical-align: top; width: 340px;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:10px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                            <!--<![endif]-->
                                                                            <div class="mobile_hide">
                                                                                <table cellpadding="0" cellspacing="0" class="social_icons" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                                <table activate="activate" align="right" alignment="alignment" cellpadding="0" cellspacing="0" class="social_table" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: undefined; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;" to="to" valign="top">
                                                                                                    <tbody>
                                                                                                        <tr align="right" style="vertical-align: top; display: inline-block; text-align: right;" valign="top">
                                                                                                            <td style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-right: 0px; padding-left: 10px;" valign="top"><a builder-element="" builder-inline-edit="" href="https://instagram.com/" target="_blank"><img builder-element="" alt="Instagram" height="32" src="{{ url('themes/yoga/images/instagram@2x.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;" title="Instagram" width="32"></a></td>
                                                                                                            <td style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-right: 0px; padding-left: 10px;" valign="top"><a builder-element="" builder-inline-edit="" href="https://www.linkedin.com/" target="_blank"><img builder-element="" alt="LinkedIn" height="32" src="{{ url('themes/yoga/images/linkedin@2x.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;" title="LinkedIn" width="32"></a></td>
                                                                                                            <td style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-right: 0px; padding-left: 10px;" valign="top"><a builder-element="" builder-inline-edit="" href="mailto:" target="_blank"><img builder-element="" alt="E-Mail" height="32" src="{{ url('themes/yoga/images/mail@2x.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;" title="E-Mail" width="32"></a></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </div>
                    </div>
                </div>
                <div builder-element="BlockElement" style="background-color:#f6f6f4;">
                    <div class="block-grid container" style="Margin: 0 auto; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                            <!--[if (mso)|(IE)]>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f6f6f4;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                            <tr class="layout-full-width" style="background-color:transparent">
                                                <![endif]-->
                                                <!--[if (mso)|(IE)]>
                                                <td align="center" width="680" style="background-color:transparent;width:680px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num12" style=" display: table-cell; vertical-align: top;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                            <!--<![endif]-->
                                                                            <!--[if !mso]><!-->
                                                                            <div class="desktop_hide" style="mso-hide: all; display: none; max-height: 0px; overflow: hidden;">
                                                                                <table cellpadding="0" cellspacing="0" class="social_icons" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; padding-top: 0px; padding-right: 10px; padding-bottom: 0px; padding-left: 10px;" valign="top">
                                                                                                <table activate="activate" align="left" alignment="alignment" cellpadding="0" cellspacing="0" class="social_table" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: undefined; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;" to="to" valign="top">
                                                                                                    <tbody>
                                                                                                        <tr align="left" style="vertical-align: top; display: inline-block; text-align: left;" valign="top">
                                                                                                            <td style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-right: 10px; padding-left: 0px;" valign="top"><a builder-element="" builder-inline-edit="" href="https://instagram.com/" target="_blank"><img builder-element="" alt="Instagram" height="32" src="{{ url('themes/yoga/images/instagram@2x.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;" title="Instagram" width="32"></a></td>
                                                                                                            <td style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-right: 10px; padding-left: 0px;" valign="top"><a builder-element="" builder-inline-edit="" href="https://www.linkedin.com/" target="_blank"><img builder-element="" alt="LinkedIn" height="32" src="{{ url('themes/yoga/images/linkedin@2x.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;" title="LinkedIn" width="32"></a></td>
                                                                                                            <td style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-right: 10px; padding-left: 0px;" valign="top"><a builder-element="" builder-inline-edit="" href="mailto:" target="_blank"><img builder-element="" alt="E-Mail" height="32" src="{{ url('themes/yoga/images/mail@2x.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;" title="E-Mail" width="32"></a></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            <!--<![endif]-->
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </div>
                    </div>
                </div>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // Experience Row widget
    class YagoExperienceRowWidget extends Widget {
        getHtmlId() {
            return "YagoExperienceRowWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb">
                        <img src="{{ url('themes/yoga/images/widget-experience-row.png') }}" />
                    </div>
                    <div class="desc">
                        <label>Experience row</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div builder-element="BlockElement" style="background-color:#f6f6f4;">
                    <div class="block-grid two-up container" style="Margin: 0 auto; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #FFFFFF;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
                            <!--[if (mso)|(IE)]>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f6f6f4;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                            <tr class="layout-full-width" style="background-color:#FFFFFF">
                                                <![endif]-->
                                                <!--[if (mso)|(IE)]>
                                                <td align="center" width="340" style="background-color:#FFFFFF;width:340px; border-top: 7px solid #f6f6f4; border-left: 7px solid #f6f6f4; border-bottom: 7px solid #f6f6f4; border-right: 7px solid #f6f6f4;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 25px; padding-left: 25px; padding-top:25px; padding-bottom:25px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num6" style="min-width: 320px; max-width: 340px; display: table-cell; vertical-align: top; width: 326px;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:7px solid #f6f6f4;  border-bottom:7px solid #f6f6f4; border-right:7px solid #f6f6f4; padding-top:25px; padding-bottom:25px; padding-right: 25px; padding-left: 25px;">
                                                                            <!--<![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 0px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#555555;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:150%;padding-top:0px;padding-bottom:0px;">
                                                                                            <div style="font-size: 12px; line-height: 18px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #555555;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 12px; line-height: 18px; margin: 0;"><strong><span>2016-2018</span></strong></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 0px; padding-bottom: 5px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#2bbbb2;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:120%;padding-top:0px;padding-bottom:5px;">
                                                                                            <div style="line-height: 14px; font-size: 12px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #2bbbb2;">
                                                                                                <p builder-element="" builder-inline-edit="" style="line-height: 21px; font-size: 12px; margin: 0;"><span style="font-size: 18px;"><strong><span>Space Head Center</span></strong></span></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 0px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#28404F;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:150%;padding-top:0px;padding-bottom:0px;">
                                                                                            <div style="font-size: 12px; line-height: 18px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #28404F;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 12px; line-height: 18px; margin: 0;"><strong><span>LOREM IPSUM</span></strong></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#555555;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:150%;padding-top:10px;padding-bottom:10px;">
                                                                                            <div style="font-size: 12px; line-height: 18px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #555555;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 14px; line-height: 21px; margin: 0;">Praesent mi sapien, accumsan eget mi quis, aliquet ullamcorper odio. Aliquam eu enim tempus sem tristique imperdi.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                                <td align="center" width="340" style="background-color:#FFFFFF;width:340px; border-top: 7px solid #f6f6f4; border-left: 7px solid #f6f6f4; border-bottom: 7px solid #f6f6f4; border-right: 7px solid #f6f6f4;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 25px; padding-left: 25px; padding-top:25px; padding-bottom:25px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num6" style="min-width: 320px; max-width: 340px; display: table-cell; vertical-align: top; width: 326px;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:7px solid #f6f6f4; border-left:7px solid #f6f6f4; border-bottom:7px solid #f6f6f4;  padding-top:25px; padding-bottom:25px; padding-right: 25px; padding-left: 25px;">
                                                                            <!--<![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 0px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#555555;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:150%;padding-top:0px;padding-bottom:0px;">
                                                                                            <div style="font-size: 12px; line-height: 18px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #555555;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 12px; line-height: 18px; margin: 0;"><strong><span>2015-2016</span></strong></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 0px; padding-bottom: 5px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#2bbbb2;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:120%;padding-top:0px;padding-bottom:5px;">
                                                                                            <div style="font-size: 12px; line-height: 14px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #2bbbb2;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 14px; line-height: 21px; margin: 0;"><span style="font-size: 18px;"><strong><span>Yoga Master Center</span></strong></span></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 0px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#28404F;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:150%;padding-top:0px;padding-bottom:0px;">
                                                                                            <div style="font-size: 12px; line-height: 18px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #28404F;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 12px; line-height: 18px; margin: 0;"><strong><span>LOREM IPSUM</span></strong></p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#555555;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:150%;padding-top:10px;padding-bottom:10px;">
                                                                                            <div style="font-size: 12px; line-height: 18px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #555555;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 14px; line-height: 21px; margin: 0;">Praesent mi sapien, accumsan eget mi quis, aliquet ullamcorper odio. Aliquam eu enim tempus sem tristique imperdi.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </div>
                    </div>
                </div>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // Footer widget
    class YagoFooterWidget extends Widget {
        getHtmlId() {
            return "YagoFooterWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb">
                        <img src="{{ url('themes/yoga/images/widget-footer.png') }}" />
                    </div>
                    <div class="desc">
                        <label>Footer</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div builder-element="BlockElement" style="background-color:#f6f6f4;">
                    <div class="block-grid container" style="Margin: 0 auto; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                            <!--[if (mso)|(IE)]>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f6f6f4;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                            <tr class="layout-full-width" style="background-color:transparent">
                                                <![endif]-->
                                                <!--[if (mso)|(IE)]>
                                                <td align="center" width="680" style="background-color:transparent;width:680px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:40px; padding-bottom:0px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num12" style=" display: table-cell; vertical-align: top;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:40px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                            <!--<![endif]-->
                                                                            <div align="center" class="img-container center fixedwidth" style="padding-right: 0px;padding-left: 0px;">
                                                                                <!--[if mso]>
                                                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                    <tr style="line-height:0px">
                                                                                        <td style="padding-right: 0px;padding-left: 0px;" align="center">
                                                                                            <![endif]--><img builder-element="" align="center" alt="Image" border="0" class="center fixedwidth" src="{{ url('themes/yoga/images/footer_img.png') }}"style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 612px; display: block;" title="Image" width="612">
                                                                                            <!--[if mso]>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </div>
                    </div>
                </div>
                <div builder-element="BlockElement" style="background-color:transparent;">
                    <div class="block-grid container" style="Margin: 0 auto; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                            <!--[if (mso)|(IE)]>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                            <tr class="layout-full-width" style="background-color:transparent">
                                                <![endif]-->
                                                <!--[if (mso)|(IE)]>
                                                <td align="center" width="680" style="background-color:transparent;width:680px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:15px; padding-bottom:15px;">
                                                                <![endif]-->
                                                                <div class="xxxcol num12" style=" display: table-cell; vertical-align: top;">
                                                                    <div style="width:100% !important;">
                                                                        <!--[if (!mso)&(!IE)]><!-->
                                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:15px; padding-bottom:15px; padding-right: 0px; padding-left: 0px;">
                                                                            <!--<![endif]-->
                                                                            <!--[if mso]>
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                        <![endif]-->
                                                                                        <div style="color:#28404F;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;line-height:120%;padding-top:10px;padding-bottom:10px;">
                                                                                            <div style="font-size: 12px; line-height: 14px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; color: #28404F;">
                                                                                                <p builder-element="" builder-inline-edit="" style="font-size: 14px; line-height: 16px; margin: 0;">© 2019 <strong><span>Etan Bonjo</span></strong> - Yoga Therapist <br>Lorem ipsum dolor sit amet</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!--[if mso]>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <![endif]-->
                                                                            <!--[if (!mso)&(!IE)]><!-->
                                                                        </div>
                                                                        <!--<![endif]-->
                                                                    </div>
                                                                </div>
                                                                <!--[if (mso)|(IE)]>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <![endif]-->
                                                    <!--[if (mso)|(IE)]>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <![endif]-->
                        </div>
                    </div>
                </div>
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    class WidgetManager {
        static init() {
            return [
                new YagoFooterWidget,
                new YagoExperienceRowWidget,
                new YagoExperienceHeaderWidget,
                new YagoAboutMeWidget,
                new YagoHeaderWidget
            ];
        }
    }
</script>
<script>   
    // Header widget
    class KidsHeaderWidget extends Widget {
        getHtmlId() {
            return "KidsHeaderWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/widget-1.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Header</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div builder-element="BlockElement" style="background-color:#ffe7b9;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffe7b9;">
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
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 0px; width: 100%;" valign="top" width="100%">
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
<div builder-element="BlockElement" style="background-color:#ffe7b9;">
                              <div class="block-grid container mixed-two-up no-stack" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffe7b9;">
                                       <tr>
                                          <td align="center">
                                             <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                                <tr class="layout-full-width" style="background-color:transparent">
                                                   <![endif]-->
                                                   <!--[if (mso)|(IE)]>
                                                   <td align="center" width="226" style="background-color:transparent;width:226px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num4" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 224px; width: 226px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td style="word-break: break-word; vertical-align: top; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                 <table align="left" cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top">
                                                                                    <tbody><tr style="vertical-align: top;" valign="top">
                                                                                       <td align="center" style="word-break: break-word; vertical-align: top; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px;" valign="top"><img builder-element="" align="center" alt="" class="icon" height="32" src="{{ url('themes/kids/images/Logo_1.png') }}" style="border:0;" width="null"></td>
                                                                                    </tr>
                                                                                 </tbody></table>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
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
                                                   <td align="center" width="453" style="background-color:transparent;width:453px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num8" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 448px; width: 453px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="right" style="word-break: break-word; vertical-align: top; padding-top: 0px; padding-bottom: 0px; padding-left: 0px; padding-right: 15px; text-align: right; font-size: 0px;" valign="top">
                                                                                 <input class="menu-checkbox" id="menunxgybe" style="display:none !important;max-height:0;visibility:hidden;" type="checkbox">
                                                                                 <div class="menu-trigger" style="display:none;max-height:0px;max-width:0px;font-size:0px;overflow:hidden;"><label class="menu-label" for="menunxgybe" style="height:36px;width:36px;display:inline-block;cursor:pointer;mso-hide:all;user-select:none;align:right;text-align:center;color:#ffffff;text-decoration:none;background-color:#090823;"><span class="menu-open" style="mso-hide:all;font-size:26px;line-height:36px;">☰</span><span class="menu-close" style="display:none;mso-hide:all;font-size:26px;line-height:36px;">✕</span></label></div>
                                                                                 <div class="menu-links">
                                                                                    <!--[if mso]>
                                                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center">
                                                                                       <tr>
                                                                                          <td style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px;display:inline;color:#090823;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:18px;text-decoration:none;letter-spacing:undefined;">Partnership</a>
                                                                                             <!--[if mso]>
                                                                                          </td>
                                                                                          <td style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px;display:inline;color:#090823;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:18px;text-decoration:none;letter-spacing:undefined;">Gifts</a>
                                                                                             <!--[if mso]>
                                                                                          </td>
                                                                                          <td style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px;display:inline;color:#090823;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:18px;text-decoration:none;letter-spacing:undefined;">Sponsor Kids</a>
                                                                                             <!--[if mso]>
                                                                                          </td>
                                                                                          <td style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px;display:inline;color:#090823;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:18px;text-decoration:none;letter-spacing:undefined;">Donation</a>
                                                                                             <!--[if mso]>
                                                                                          </td>
                                                                                       </tr>
                                                                                    </table>
                                                                                    <![endif]-->
                                                                                 </div>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
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
<div builder-element="BlockElement" style="background-color:#ffe7b9;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffe7b9;">
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
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 0px; width: 100%;" valign="top" width="100%">
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
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // Intro widget
    class KidsIntroWidget extends Widget {
        getHtmlId() {
            return "KidsIntroWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/widget-2.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Intro</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div builder-element="BlockElement" style="background-color:#ffe7b9;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffe7b9;">
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
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <div align="center" class="img-container center fixedwidth fullwidthOnMobile big" style="padding-right: 0px;padding-left: 0px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                              <tr style="line-height:0px">
                                                                                 <td style="padding-right: 0px;padding-left: 0px;" align="center">
                                                                                    <![endif]--><img builder-element="" align="center" alt="Happy Kids Looking" border="0" class="center fixedwidth fullwidthOnMobile" src="{{ url('themes/kids/images/happy_kids.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 646px; max-width: 100%; display: block;" title="Happy Kids Looking" width="646">
                                                                                    <!--[if mso]>
                                                                                 </td>
                                                                              </tr>
                                                                           </table>
                                                                           <![endif]-->
                                                                        </div>
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="5" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 5px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="5" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>Give a gift. Change a life.</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#5f6266;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #5f6266; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</span></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <div align="center" class="button-container" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                              <tr>
                                                                                 <td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="center">
                                                                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="www.example.com" style="height:33pt;width:120pt;v-text-anchor:middle;" arcsize="10%" strokeweight="0.75pt" strokecolor="#F18063" fillcolor="#f18063">
                                                                                       <w:anchorlock/>
                                                                                       <v:textbox inset="0,0,0,0">
                                                                                          <center style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #ffffff; background-color: #f18063; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width: auto; width: auto; border-top: 1px solid #F18063; border-right: 1px solid #F18063; border-bottom: 1px solid #F18063; border-left: 1px solid #F18063; padding-top: 5px; padding-bottom: 5px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;" target="_blank"><span style="padding-left:35px;padding-right:35px;font-size:16px;display:inline-block;letter-spacing:undefined;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Donate Now</span></span></a>
                                                                                             <!--[if mso]>
                                                                                          </center>
                                                                                       </v:textbox>
                                                                                    </v:roundrect>
                                                                                 </td>
                                                                              </tr>
                                                                           </table>
                                                                           <![endif]-->
                                                                        </div>
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="5" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 5px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="5" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // Wave widget
    class KidsWaveWidget extends Widget {
        getHtmlId() {
            return "KidsWaveWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/widget-3.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Divider</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
            <div builder-element="BlockElement" style="background-image:url('/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDMvaW1hZ2Vz/wave_separtor.png');background-position:top center;background-repeat:repeat;background-color:transparent;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-image:url('images/wave_separtor.png');background-position:top center;background-repeat:repeat;background-color:transparent;">
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
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="45" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 45px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="45" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
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
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // Image left widget
    class KidsImageLeftWidget extends Widget {
        getHtmlId() {
            return "KidsImageLeftWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/widget-4.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Left Image</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
            <div builder-element="BlockElement" style="background-color:transparent;">
                              <div class="block-grid container two-up" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
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
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num6" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 336px; width: 340px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <div align="center" class="img-container center fixedwidth fullwidthOnMobile big" style="padding-right: 0px;padding-left: 0px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                              <tr style="line-height:0px">
                                                                                 <td style="padding-right: 0px;padding-left: 0px;" align="center">
                                                                                    <![endif]--><img builder-element="" align="center" alt="Kid eating" border="0" class="center fixedwidth fullwidthOnMobile" src="{{ url('themes/kids/images/Starving_child.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 340px; max-width: 100%; display: block;" title="Kid eating" width="340">
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
                                                   <td align="center" width="340" style="background-color:transparent;width:340px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num6" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 336px; width: 340px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="20" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 20px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="20" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#f18063;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:0px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #f18063; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Food Donation</span></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:left;margin-top:0;margin-bottom:0;"><strong>FOOD SECURITY</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#5f6266;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #5f6266; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. </span></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <div align="left" class="button-container" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                              <tr>
                                                                                 <td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="left">
                                                                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="www.example.com" style="height:33pt;width:120pt;v-text-anchor:middle;" arcsize="10%" strokeweight="0.75pt" strokecolor="#F18063" fillcolor="#f18063">
                                                                                       <w:anchorlock/>
                                                                                       <v:textbox inset="0,0,0,0">
                                                                                          <center style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #ffffff; background-color: #f18063; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width: auto; width: auto; border-top: 1px solid #F18063; border-right: 1px solid #F18063; border-bottom: 1px solid #F18063; border-left: 1px solid #F18063; padding-top: 5px; padding-bottom: 5px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;" target="_blank"><span style="padding-left:35px;padding-right:35px;font-size:16px;display:inline-block;letter-spacing:undefined;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Donate Now</span></span></a>
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

    // Stats widget
    class KidsStatsWidget extends Widget {
        getHtmlId() {
            return "KidsStatsWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/widget-5.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Stats</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
            <div builder-element="BlockElement" style="background-color:transparent;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
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
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
                              <div class="block-grid container four-up" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;">
                                       <tr>
                                          <td align="center">
                                             <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                                <tr class="layout-full-width" style="background-color:transparent">
                                                   <![endif]-->
                                                   <!--[if (mso)|(IE)]>
                                                   <td align="center" width="170" style="background-color:transparent;width:170px; border-top: 2px solid #FDF1DA; border-left: 2px solid #FDF1DA; border-bottom: 2px solid #FDF1DA; border-right: 2px solid #FDF1DA;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;background-color:#ffffff;">
                                                               <![endif]-->
                                                               <div class="col num3" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 168px; background-color: #ffffff; width: 166px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:2px solid #FDF1DA; border-left:2px solid #FDF1DA; border-bottom:2px solid #FDF1DA; border-right:2px solid #FDF1DA; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>20 000</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#5f6266;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #5f6266; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Volunteers</span></p>
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
                                                   <td align="center" width="170" style="background-color:transparent;width:170px; border-top: 2px solid #FDF1DA; border-left: 2px solid #FDF1DA; border-bottom: 2px solid #FDF1DA; border-right: 2px solid #FDF1DA;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;background-color:#ffffff;">
                                                               <![endif]-->
                                                               <div class="col num3" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 168px; background-color: #ffffff; width: 166px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:2px solid #FDF1DA; border-left:2px solid #FDF1DA; border-bottom:2px solid #FDF1DA; border-right:2px solid #FDF1DA; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>10 000</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#5f6266;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #5f6266; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Donations</span></p>
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
                                                   <td align="center" width="170" style="background-color:transparent;width:170px; border-top: 2px solid #FDF1DA; border-left: 2px solid #FDF1DA; border-bottom: 2px solid #FDF1DA; border-right: 2px solid #FDF1DA;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;background-color:#ffffff;">
                                                               <![endif]-->
                                                               <div class="col num3" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 168px; background-color: #ffffff; width: 166px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:2px solid #FDF1DA; border-left:2px solid #FDF1DA; border-bottom:2px solid #FDF1DA; border-right:2px solid #FDF1DA; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>23</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#5f6266;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #5f6266; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Countries</span></p>
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
                                                   <td align="center" width="170" style="background-color:transparent;width:170px; border-top: 2px solid #FDF1DA; border-left: 2px solid #FDF1DA; border-bottom: 2px solid #FDF1DA; border-right: 2px solid #FDF1DA;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;background-color:#ffffff;">
                                                               <![endif]-->
                                                               <div class="col num3" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 168px; background-color: #ffffff; width: 166px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:2px solid #FDF1DA; border-left:2px solid #FDF1DA; border-bottom:2px solid #FDF1DA; border-right:2px solid #FDF1DA; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 5px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>40+</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#5f6266;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #5f6266; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Causes</span></p>
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
<div builder-element="BlockElement" style="background-color:transparent;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
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
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // Image right widget
    class KidsImageRightWidget extends Widget {
        getHtmlId() {
            return "KidsImageRightWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/widget-6.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Right Image</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
                <div builder-element="BlockElement" style="background-image:url('/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDMvaW1hZ2Vz/middle_bg.png');background-position:center top;background-repeat:no-repeat;background-color:transparent;">
                              <div class="block-grid container two-up" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;;direction:rtl">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-image:url('images/middle_bg.png');background-position:center top;background-repeat:no-repeat;background-color:transparent;">
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
                                                               <div class="col num6" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 336px; width: 340px; direction: ltr;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 0px; width: 100%;" valign="top" width="100%">
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
                                                                        <div align="center" class="img-container center fixedwidth fullwidthOnMobile big" style="padding-right: 0px;padding-left: 0px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                              <tr style="line-height:0px">
                                                                                 <td style="padding-right: 0px;padding-left: 0px;" align="center">
                                                                                    <![endif]--><img builder-element="" align="center" alt="Kids in Hospital" border="0" class="center fixedwidth fullwidthOnMobile" src="{{ url('themes/kids/images/kids_hospital.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 340px; max-width: 100%; display: block;" title="Kids in Hospital" width="340">
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
                                                   <td align="center" width="340" style="background-color:transparent;width:340px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num6" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 336px; width: 340px; direction: ltr;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="15" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 15px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="15" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#f18063;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:0px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #f18063; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Monthly Donation</span></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:left;margin-top:0;margin-bottom:0;"><strong>Give Where Most Needed</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#5f6266;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #5f6266; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. </span></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <div align="left" class="button-container" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                              <tr>
                                                                                 <td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="left">
                                                                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="www.example.com" style="height:33pt;width:120pt;v-text-anchor:middle;" arcsize="10%" strokeweight="0.75pt" strokecolor="#F18063" fillcolor="#f18063">
                                                                                       <w:anchorlock/>
                                                                                       <v:textbox inset="0,0,0,0">
                                                                                          <center style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #ffffff; background-color: #f18063; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width: auto; width: auto; border-top: 1px solid #F18063; border-right: 1px solid #F18063; border-bottom: 1px solid #F18063; border-left: 1px solid #F18063; padding-top: 5px; padding-bottom: 5px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;" target="_blank"><span style="padding-left:35px;padding-right:35px;font-size:16px;display:inline-block;letter-spacing:undefined;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Donate Now</span></span></a>
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

    // Gallery widget
    class KidsGalleryWidget extends Widget {
        getHtmlId() {
            return "KidsGalleryWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/widget-7.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Gallery</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
            <div builder-element="BlockElement" style="background-color:transparent;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
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
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#f18063;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:0px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #f18063; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Monthly Donation</span></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>We work in the hardest-to-reach places, where it’s toughest to be a child.</strong><br></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
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
<div builder-element="BlockElement" style="background-image:url('/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDMvaW1hZ2Vz/bg_images.png');background-position:center top;background-repeat:no-repeat;background-color:transparent;">
                              <div class="block-grid container two-up" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-image:url('images/bg_images.png');background-position:center top;background-repeat:no-repeat;background-color:transparent;">
                                       <tr>
                                          <td align="center">
                                             <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                                <tr class="layout-full-width" style="background-color:transparent">
                                                   <![endif]-->
                                                   <!--[if (mso)|(IE)]>
                                                   <td align="center" width="340" style="background-color:transparent;width:340px; border-top: none; border-left: none; border-bottom: none; border-right: none;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr bgcolor='#FDF1DA'>
                                                            <td colspan='3' style='font-size:7px;line-height:10px'>&nbsp;</td>
                                                         </tr>
                                                         <tr>
                                                            <td style='padding-top:5px;padding-bottom:5px' width='10' bgcolor='#FDF1DA'>
                                                               <table role='presentation' width='10' cellpadding='0' cellspacing='0' border='0'>
                                                                  <tr>
                                                                     <td>&nbsp;</td>
                                                                  </tr>
                                                               </table>
                                                            </td>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num6" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 336px; width: 320px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:10px solid #FDF1DA; border-left:10px solid #FDF1DA; border-bottom:10px solid #FDF1DA; border-right:10px solid #FDF1DA; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <div align="center" class="img-container center fixedwidth fullwidthOnMobile big" style="padding-right: 0px;padding-left: 0px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                              <tr style="line-height:0px">
                                                                                 <td style="padding-right: 0px;padding-left: 0px;" align="center">
                                                                                    <![endif]--><img builder-element="" align="center" alt="Kids showing Peace sign" border="0" class="center fixedwidth fullwidthOnMobile" src="{{ url('themes/kids/images/donation_2_2.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 320px; max-width: 100%; display: block;" title="Kids showing Peace sign" width="320">
                                                                                    <!--[if mso]>
                                                                                 </td>
                                                                              </tr>
                                                                           </table>
                                                                           <![endif]-->
                                                                        </div>
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 0px; width: 100%;" valign="top" width="100%">
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
                                                                        <div align="center" class="img-container center fullwidthOnMobile fixedwidth big" style="padding-right: 0px;padding-left: 0px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                              <tr style="line-height:0px">
                                                                                 <td style="padding-right: 0px;padding-left: 0px;" align="center">
                                                                                    <![endif]--><img builder-element="" align="center" alt="Boy running with guitar" border="0" class="center fullwidthOnMobile fixedwidth" src="{{ url('themes/kids/images/donation_1_1.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 320px; max-width: 100%; display: block;" title="Boy running with guitar" width="320">
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
                                                            <td style='padding-top:5px;padding-bottom:5px' width='10' bgcolor='#FDF1DA'>
                                                               <table role='presentation' width='10' cellpadding='0' cellspacing='0' border='0'>
                                                                  <tr>
                                                                     <td>&nbsp;</td>
                                                                  </tr>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                         <tr bgcolor='#FDF1DA'>
                                                            <td colspan='3' style='font-size:7px;line-height:10px'>&nbsp;</td>
                                                         </tr>
                                                      </table>
                                                      <![endif]-->
                                                      <!--[if (mso)|(IE)]>
                                                   </td>
                                                   <td align="center" width="340" style="background-color:transparent;width:340px; border-top: none; border-left: none; border-bottom: none; border-right: none;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr bgcolor='#FDF1DA'>
                                                            <td colspan='3' style='font-size:7px;line-height:10px'>&nbsp;</td>
                                                         </tr>
                                                         <tr>
                                                            <td style='padding-top:5px;padding-bottom:5px' width='10' bgcolor='#FDF1DA'>
                                                               <table role='presentation' width='10' cellpadding='0' cellspacing='0' border='0'>
                                                                  <tr>
                                                                     <td>&nbsp;</td>
                                                                  </tr>
                                                               </table>
                                                            </td>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num6" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 336px; width: 320px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:10px solid #FDF1DA; border-left:10px solid #FDF1DA; border-bottom:10px solid #FDF1DA; border-right:10px solid #FDF1DA; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <div align="center" class="img-container center fixedwidth fullwidthOnMobile big" style="padding-right: 0px;padding-left: 0px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                              <tr style="line-height:0px">
                                                                                 <td style="padding-right: 0px;padding-left: 0px;" align="center">
                                                                                    <![endif]--><img builder-element="" align="center" alt="Food Donation" border="0" class="center fixedwidth fullwidthOnMobile" src="{{ url('themes/kids/images/Donation_3_1.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 320px; max-width: 100%; display: block;" title="Food Donation" width="320">
                                                                                    <!--[if mso]>
                                                                                 </td>
                                                                              </tr>
                                                                           </table>
                                                                           <![endif]-->
                                                                        </div>
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 0px; width: 100%;" valign="top" width="100%">
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
                                                                        <div align="center" class="img-container center fixedwidth fullwidthOnMobile big" style="padding-right: 0px;padding-left: 0px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                              <tr style="line-height:0px">
                                                                                 <td style="padding-right: 0px;padding-left: 0px;" align="center">
                                                                                    <![endif]--><img builder-element="" align="center" alt="Child looking at camera" border="0" class="center fixedwidth fullwidthOnMobile" src="{{ url('themes/kids/images/Donation_4_1.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 320px; max-width: 100%; display: block;" title="Child looking at camera" width="320">
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
                                                            <td style='padding-top:5px;padding-bottom:5px' width='10' bgcolor='#FDF1DA'>
                                                               <table role='presentation' width='10' cellpadding='0' cellspacing='0' border='0'>
                                                                  <tr>
                                                                     <td>&nbsp;</td>
                                                                  </tr>
                                                               </table>
                                                            </td>
                                                         </tr>
                                                         <tr bgcolor='#FDF1DA'>
                                                            <td colspan='3' style='font-size:7px;line-height:10px'>&nbsp;</td>
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
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
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
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // Donate widget
    class KidsDonateWidget extends Widget {
        getHtmlId() {
            return "KidsDonateWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/widget-8.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Donate</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
            <div builder-element="BlockElement" style="background-color:#f4a664;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4a664;">
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
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
<div builder-element="BlockElement" style="background-image:url('/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDMvaW1hZ2Vz/bg_donate.png');background-position:center top;background-repeat:no-repeat;background-color:#f4a664;">
                              <div class="block-grid container mixed-two-up" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-image:url('images/bg_donate.png');background-position:center top;background-repeat:no-repeat;background-color:#f4a664;">
                                       <tr>
                                          <td align="center">
                                             <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                                <tr class="layout-full-width" style="background-color:transparent">
                                                   <![endif]-->
                                                   <!--[if (mso)|(IE)]>
                                                   <td align="center" width="453" style="background-color:transparent;width:453px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num8" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 448px; width: 453px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#ffffff;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:0px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #ffffff; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Monthly Donation</span></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#ffffff;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:left;margin-top:0;margin-bottom:0;"><strong>Give Where Most Needed</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#ffffff;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #ffffff; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. </span></p>
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
                                                   <td align="center" width="226" style="background-color:transparent;width:226px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num4" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 224px; width: 226px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="25" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 25px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="25" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <div align="center" class="button-container" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                              <tr>
                                                                                 <td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="center">
                                                                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="www.example.com" style="height:33pt;width:120pt;v-text-anchor:middle;" arcsize="10%" strokeweight="0.75pt" strokecolor="#FDF1DA" fillcolor="#fdf1da">
                                                                                       <w:anchorlock/>
                                                                                       <v:textbox inset="0,0,0,0">
                                                                                          <center style="color:#e2781b; font-family:Arial, sans-serif; font-size:16px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #e2781b; background-color: #fdf1da; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width: auto; width: auto; border-top: 1px solid #FDF1DA; border-right: 1px solid #FDF1DA; border-bottom: 1px solid #FDF1DA; border-left: 1px solid #FDF1DA; padding-top: 5px; padding-bottom: 5px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;" target="_blank"><span style="padding-left:35px;padding-right:35px;font-size:16px;display:inline-block;letter-spacing:undefined;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Donate Now</span></span></a>
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
<div builder-element="BlockElement" style="background-color:#f4a664;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4a664;">
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
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // Plans widget
    class KidsPlansWidget extends Widget {
        getHtmlId() {
            return "KidsPlansWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/widget-9.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Plans</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
            <div builder-element="BlockElement" style="background-color:transparent;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
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
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 0px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#f18063;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:0px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="font-size: 14px; line-height: 1.5; color: #f18063; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 24px; margin-top: 0; margin-bottom: 0;"><span style="font-size: 16px;">Monthly Donation</span></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>Meet children waiting for a sponsor</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="15" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 15px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="15" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
                              <div class="block-grid container three-up" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: #ffffff;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;">
                                       <tr>
                                          <td align="center">
                                             <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                                <tr class="layout-full-width" style="background-color:#ffffff">
                                                   <![endif]-->
                                                   <!--[if (mso)|(IE)]>
                                                   <td align="center" width="226" style="background-color:#ffffff;width:226px; border-top: 2px solid #E9E9F0; border-left: 2px solid #E9E9F0; border-bottom: 2px solid #E9E9F0; border-right: 2px solid #E9E9F0;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 10px; padding-left: 10px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num4" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 224px; width: 222px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:2px solid #E9E9F0; border-left:2px solid #E9E9F0; border-bottom:2px solid #E9E9F0; border-right:2px solid #E9E9F0; padding-top:5px; padding-bottom:5px; padding-right: 10px; padding-left: 10px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="5" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 5px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="5" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>Sponsor 1 Kid</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#848484;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.5; font-size: 12px; color: #848484; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 18px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;">Lorem ipsum dolor sit amet, consectetur sit amet, consectetur </p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="25" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 25px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="25" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 30px; padding-bottom: 5px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#232f3d;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:30px;padding-right:10px;padding-bottom:5px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; color: #232f3d; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.2; text-align: center; word-break: break-word; mso-line-height-alt: 19px; margin-top: 0; margin-bottom: 0;"><strong>$19/month</strong></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <div align="center" class="button-container" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                              <tr>
                                                                                 <td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="center">
                                                                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="www.example.com" style="height:33pt;width:120pt;v-text-anchor:middle;" arcsize="10%" strokeweight="0.75pt" strokecolor="#F18063" fillcolor="#f18063">
                                                                                       <w:anchorlock/>
                                                                                       <v:textbox inset="0,0,0,0">
                                                                                          <center style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #ffffff; background-color: #f18063; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width: auto; width: auto; border-top: 1px solid #F18063; border-right: 1px solid #F18063; border-bottom: 1px solid #F18063; border-left: 1px solid #F18063; padding-top: 5px; padding-bottom: 5px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;" target="_blank"><span style="padding-left:35px;padding-right:35px;font-size:16px;display:inline-block;letter-spacing:undefined;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Donate Now</span></span></a>
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
                                                   <td align="center" width="226" style="background-color:#ffffff;width:226px; border-top: 2px solid #F4A664; border-left: 2px solid #F4A664; border-bottom: 2px solid #F4A664; border-right: 2px solid #F4A664;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 10px; padding-left: 10px; padding-top:5px; padding-bottom:5px;background-color:#f4a664;">
                                                               <![endif]-->
                                                               <div class="col num4" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 224px; background-color: #f4a664; width: 222px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:2px solid #F4A664; border-left:2px solid #F4A664; border-bottom:2px solid #F4A664; border-right:2px solid #F4A664; padding-top:5px; padding-bottom:5px; padding-right: 10px; padding-left: 10px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="5" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 5px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="5" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#ffffff;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>Sponsor 2 Kids</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#ffffff;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.5; font-size: 12px; color: #ffffff; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 18px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;">Lorem ipsum dolor sit amet, consectetur sit amet, consectetur </p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="25" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 25px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="25" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 30px; padding-bottom: 5px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#232f3d;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:30px;padding-right:10px;padding-bottom:5px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; color: #232f3d; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.2; text-align: center; word-break: break-word; mso-line-height-alt: 19px; margin-top: 0; margin-bottom: 0;"><strong>$19/month</strong></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <div align="center" class="button-container" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                              <tr>
                                                                                 <td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="center">
                                                                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="www.example.com" style="height:33pt;width:120pt;v-text-anchor:middle;" arcsize="10%" strokeweight="0.75pt" strokecolor="#FDF1DA" fillcolor="#fdf1da">
                                                                                       <w:anchorlock/>
                                                                                       <v:textbox inset="0,0,0,0">
                                                                                          <center style="color:#e2781b; font-family:Arial, sans-serif; font-size:16px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #e2781b; background-color: #fdf1da; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width: auto; width: auto; border-top: 1px solid #FDF1DA; border-right: 1px solid #FDF1DA; border-bottom: 1px solid #FDF1DA; border-left: 1px solid #FDF1DA; padding-top: 5px; padding-bottom: 5px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;" target="_blank"><span style="padding-left:35px;padding-right:35px;font-size:16px;display:inline-block;letter-spacing:undefined;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Donate Now</span></span></a>
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
                                                   <td align="center" width="226" style="background-color:#ffffff;width:226px; border-top: 2px solid #E9E9F0; border-left: 2px solid #E9E9F0; border-bottom: 2px solid #E9E9F0; border-right: 2px solid #E9E9F0;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 10px; padding-left: 10px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num4" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 224px; width: 222px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:2px solid #E9E9F0; border-left:2px solid #E9E9F0; border-bottom:2px solid #E9E9F0; border-right:2px solid #E9E9F0; padding-top:5px; padding-bottom:5px; padding-right: 10px; padding-left: 10px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="5" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 5px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="5" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; padding-top: 10px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#090823;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:31px;font-weight:normal;letter-spacing:normal;line-height:120%;text-align:center;margin-top:0;margin-bottom:0;"><strong>Sponsor More Kids</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#848484;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.5; font-size: 12px; color: #848484; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 18px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: center; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;">Lorem ipsum dolor sit amet, consectetur sit amet, consectetur </p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="25" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 25px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="25" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                          </tr>
                                                                                       </tbody>
                                                                                    </table>
                                                                                 </td>
                                                                              </tr>
                                                                           </tbody>
                                                                        </table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 10px; padding-left: 10px; padding-top: 30px; padding-bottom: 5px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#232f3d;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:30px;padding-right:10px;padding-bottom:5px;padding-left:10px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; color: #232f3d; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 16px; line-height: 1.2; text-align: center; word-break: break-word; mso-line-height-alt: 19px; margin-top: 0; margin-bottom: 0;"><strong>$19/month</strong></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <div align="center" class="button-container" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                                                                           <!--[if mso]>
                                                                           <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                              <tr>
                                                                                 <td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="center">
                                                                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="www.example.com" style="height:33pt;width:113.25pt;v-text-anchor:middle;" arcsize="10%" strokeweight="0.75pt" strokecolor="#F18063" fillcolor="#f18063">
                                                                                       <w:anchorlock/>
                                                                                       <v:textbox inset="0,0,0,0">
                                                                                          <center style="color:#ffffff; font-family:Arial, sans-serif; font-size:16px">
                                                                                             <![endif]--><a builder-element="" href="/assets/YXBwL2N1c3RvbWVycy81OTY2NGYwNmQwYjQ4L2hvbWUvdGVtcGxhdGVzLzYxMjQ5ZGI3OTgxNDM/www.example.com" style="-webkit-text-size-adjust: none; text-decoration: none; display: inline-block; color: #ffffff; background-color: #f18063; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; width: auto; width: auto; border-top: 1px solid #F18063; border-right: 1px solid #F18063; border-bottom: 1px solid #F18063; border-left: 1px solid #F18063; padding-top: 5px; padding-bottom: 5px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; text-align: center; mso-border-alt: none; word-break: keep-all;" target="_blank"><span style="padding-left:35px;padding-right:35px;font-size:16px;display:inline-block;letter-spacing:undefined;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Contact Us</span></span></a>
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
<div builder-element="BlockElement" style="background-color:transparent;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
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
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }

    // Footer widget
    class KidsFooterWidget extends Widget {
        getHtmlId() {
            return "KidsFooterWidget";
        }

        init() {
            // default button html
            this.setButtonHtml(`
                <div class="content-widget-row">
                    <div class="widget-thumb widget-thumb-kids">
                        <img src="{{ url('themes/kids/images/widget-91.png') }}"/>
                    </div>
                    <div class="desc">
                        <label>Footer</label>
                    </div>
                </div>
            `);

            // default content html
            this.setContentHtml(`
            <div builder-element="BlockElement" style="background-color:#251f20;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#251f20;">
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
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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
<div builder-element="BlockElement" style="background-color:#251f20;">
                              <div class="block-grid container three-up" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#251f20;">
                                       <tr>
                                          <td align="center">
                                             <table cellpadding="0" cellspacing="0" border="0" style="width:680px">
                                                <tr class="layout-full-width" style="background-color:transparent">
                                                   <![endif]-->
                                                   <!--[if (mso)|(IE)]>
                                                   <td align="center" width="226" style="background-color:transparent;width:226px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num4" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 224px; width: 226px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 0px; padding-left: 20px; padding-right: 0px; padding-top: 0px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#ffffff;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:18px;font-weight:normal;line-height:200%;text-align:left;margin-top:0;margin-bottom:0;"><strong>About Us</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#ffffff;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:2;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 2; font-size: 12px; color: #ffffff; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 24px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 2; word-break: break-word; mso-line-height-alt: 28px; margin-top: 0; margin-bottom: 0;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, </p>
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
                                                   <td align="center" width="226" style="background-color:transparent;width:226px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num4" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 224px; width: 226px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 0px; padding-left: 20px; padding-right: 0px; padding-top: 0px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#ffffff;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:18px;font-weight:normal;line-height:200%;text-align:left;margin-top:0;margin-bottom:0;"><strong>Links</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#a9a9a9;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; color: #a9a9a9; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin-top: 0; margin-bottom: 0;"><a builder-element="" href="http://www.example.com" rel="noopener" style="text-decoration: none; color: #ffffff;" target="_blank">Donations</a></p>
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
                                                                              <td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#a9a9a9;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; color: #a9a9a9; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin-top: 0; margin-bottom: 0;"><a builder-element="" href="http://www.example.com" rel="noopener" style="text-decoration: none; color: #ffffff;" target="_blank">Sponsoring</a></p>
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
                                                                              <td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#a9a9a9;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; color: #a9a9a9; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin-top: 0; margin-bottom: 0;"><a builder-element="" href="http://www.example.com" rel="noopener" style="text-decoration: none; color: #ffffff;" target="_blank">Gift Planning</a></p>
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
                                                                              <td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#a9a9a9;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; color: #a9a9a9; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin-top: 0; margin-bottom: 0;"><a builder-element="" href="http://www.example.com" rel="noopener" style="text-decoration: none; color: #ffffff;" target="_blank">Blog</a></p>
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
                                                                              <td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#a9a9a9;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; color: #a9a9a9; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin-top: 0; margin-bottom: 0;"><a builder-element="" href="http://www.example.com" rel="noopener" style="text-decoration: none; color: #ffffff;" target="_blank">Unsubscribe</a></p>
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
                                                   <td align="center" width="226" style="background-color:transparent;width:226px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top">
                                                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                         <tr>
                                                            <td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;">
                                                               <![endif]-->
                                                               <div class="col num4" style="display: table-cell; vertical-align: top; max-width: 320px; min-width: 224px; width: 226px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody><tr style="vertical-align: top;" valign="top">
                                                                              <td align="center" style="word-break: break-word; vertical-align: top; padding-bottom: 0px; padding-left: 20px; padding-right: 0px; padding-top: 0px; text-align: center; width: 100%;" valign="top" width="100%">
                                                                                 <h1 builder-element="" style="color:#ffffff;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:18px;font-weight:normal;line-height:200%;text-align:left;margin-top:0;margin-bottom:0;"><strong>Contact</strong></h1>
                                                                              </td>
                                                                           </tr>
                                                                        </tbody></table>
                                                                        <!--[if mso]>
                                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                           <tr>
                                                                              <td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#a9a9a9;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; color: #a9a9a9; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin-top: 0; margin-bottom: 0;"><a builder-element="" href="http://www.example.com" rel="noopener" style="text-decoration: none; color: #ffffff;" target="_blank">Info@company.com</a></p>
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
                                                                              <td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif">
                                                                                 <![endif]-->
                                                                                 <div style="color:#a9a9a9;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;">
                                                                                    <div class="txtTinyMce-wrapper" style="line-height: 1.2; font-size: 12px; color: #a9a9a9; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 14px;">
                                                                                       <p builder-element="" style="margin: 0; font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin-top: 0; margin-bottom: 0;"><a builder-element="" href="http://www.example.com" rel="noopener" style="text-decoration: none; color: #ffffff;" target="_blank">Help Center</a></p>
                                                                                    </div>
                                                                                 </div>
                                                                                 <!--[if mso]>
                                                                              </td>
                                                                           </tr>
                                                                        </table>
                                                                        <![endif]-->
                                                                        <table cellpadding="0" cellspacing="0" class="social_icons" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td style="word-break: break-word; vertical-align: top; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 20px;" valign="top">
                                                                                    <table align="left" cellpadding="0" cellspacing="0" class="social_table" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;" valign="top">
                                                                                       <tbody>
                                                                                          <tr align="left" style="vertical-align: top; display: inline-block; text-align: left;" valign="top">
                                                                                             <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 0px;" valign="top"><a builder-element="" href="https://www.facebook.com/" target="_blank"><img builder-element="" alt="Facebook" height="32" src="{{ url('themes/kids/images/facebook2x.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;" title="facebook" width="32"></a></td>
                                                                                             <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 0px;" valign="top"><a builder-element="" href="https://www.twitter.com/" target="_blank"><img builder-element="" alt="Twitter" height="32" src="{{ url('themes/kids/images/twitter2x.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;" title="twitter" width="32"></a></td>
                                                                                             <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 0px;" valign="top"><a builder-element="" href="https://www.linkedin.com/" target="_blank"><img builder-element="" alt="Linkedin" height="32" src="{{ url('themes/kids/images/linkedin2x.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;" title="linkedin" width="32"></a></td>
                                                                                             <td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 4px; padding-left: 0px;" valign="top"><a builder-element="" href="https://www.instagram.com/" target="_blank"><img builder-element="" alt="Instagram" height="32" src="{{ url('themes/kids/images/instagram2x.png') }}" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; display: block;" title="instagram" width="32"></a></td>
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
<div builder-element="BlockElement" style="background-color:#251f20;">
                              <div class="block-grid container" style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; Margin: 0 auto; background-color: transparent;">
                                 <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                                    <!--[if (mso)|(IE)]>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#251f20;">
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
                                                               <div class="col num12" style="display: table-cell; vertical-align: top; width: 680px;">
                                                                  <div class="col_cont" style="width:100% !important;">
                                                                     <!--[if (!mso)&(!IE)]><!-->
                                                                     <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                           <tbody>
                                                                              <tr style="vertical-align: top;" valign="top">
                                                                                 <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="30" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 30px; width: 100%;" valign="top" width="100%">
                                                                                       <tbody>
                                                                                          <tr style="vertical-align: top;" valign="top">
                                                                                             <td height="30" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
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

            `);

            // default dragging html
            this.setDraggingHtml(this.getButtonHtml());
        }
    }


    class WidgetManager {
        static init() {
            return [
                new KidsFooterWidget,
                new KidsPlansWidget,
                new KidsDonateWidget,
                new KidsGalleryWidget,
                new KidsImageRightWidget,
                new KidsStatsWidget,
                new KidsImageLeftWidget,
                new KidsWaveWidget,
                new KidsIntroWidget,
                new KidsHeaderWidget
            ];
        }
    }
</script>
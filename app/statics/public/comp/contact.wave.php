<div class="vide-1">
    <div id="contact" class="contact">
        <wv-comp.form-alert Type={$alertType} Text={{#$alertText#}}/>
        <h3>Contact</h3>
         <div class="contact-grids">
            <div class="contact-icons">
                <div class="contact-grid">
                        <div class="contact-fig">
                                <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span>
                        </div>
                        <p>030-320-9568</p>
                </div>
                <div class="contact-grid">
                        <div class="contact-fig1">
                                <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                        </div>
                        <p>Lumumba Loop
                        <span>Tema, Community 2</span></p>
                </div>
                <div class="contact-grid">
                        <div class="contact-fig2">
                                <span class="glyphicon glyphicon-envelope2" aria-hidden="true"></span>
                        </div>
                        <p><a href="mailto:info@ilapi.org">info@ilapi.org</a></p>
                </div>
                <div class="clearfix"> </div>
            </div>
            <form action="{# url(':sendMessage') #}" name="contact_form" id="contact_form" method="post" > 
                <input type="text" name="fullName" id="contact_name" value="{# !empty($reqValues) ? $reqValues['fullName']: '' #}" placeholder="Full Name" />
                <input type="text" name="email" id="contact_email" value="{# !empty($reqValues) ? $reqValues['email']: '' #}" placeholder="Email" />
                <input type="text" name="subject" id="contact_subject" value="{# !empty($reqValues) ? $reqValues['subject']: '' #}" placeholder="Subject" />
                <textarea name="message" id="contact_message"  placeholder="Type Message...">
                {# !empty($reqValues) ? $reqValues['message']: '' #}
                </textarea><input type="submit" name="send" id="send" value="SEND"  />
                {# @CSRF #}
            </form>
        </div>			 
        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3970.558942169602!2d-0.013985785730799147!3d5.631927495917294!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdf87445834f2db%3A0x8a46b67774e05a33!2sInstitute+for+Liberty+and+Policy+Innovation+(ILAPI)!5e0!3m2!1sen!2sgh!4v1519393620556" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
    </div>			
    </div>
 
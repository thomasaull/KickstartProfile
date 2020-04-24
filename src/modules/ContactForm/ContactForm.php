<?php namespace ProcessWire; ?>

<div class="ContactForm">

  <?php if(isset($text)): ?>
  <div class="ContactForm-text defaultText"><?=Helper::replaceQuotes($text)?></div>
  <?php endif; ?>

  <noscript>
    <div class="defaultText NoJsWarning">
      <b>Hinweis: Das Kontaktformular funktioniert nur mit eingeschaltetem Javascript.</b>
    </div>
  </noscript>

  <form class="ContactForm-form" action="/api/contactForm" method="POST" ref="form">

    <div class="ContactForm-inputWrapper contactForm-inputWrapper--name">
      <input class="ContactForm-inputText" placeholder="" type="text" name="name"
        v-validate="'required'"
        data-vv-as="Name"
        :class="{'ContactForm-inputText--error': hasError('name')}"
        v-model="form.name"
      >
      <div class="ContactForm-label" :class="{'ContactForm-label--filled': isFilled(form.name) }">Name</div>
      <div v-show="hasError('name')" class="ContactForm-error" v-cloak>{{ errors.first('name') }}</div>
    </div>

    <div class="ContactForm-inputWrapper ContactForm-inputWrapper--email">
      <input class="ContactForm-inputText" placeholder="" type="text" name="email"
        v-validate="'required|email'"
        data-vv-as="E-Mail"
        :class="{'ContactForm-inputText--error': hasError('email')}"
        v-model="form.email"
      >

     <div class="ContactForm-label" :class="{'ContactForm-label--filled': isFilled(form.email) }">E-Mail</div>
      <div v-show="hasError('email')" class="ContactForm-error" v-cloak>{{ errors.first('email') }}</div>
    </div>

    <div class="ContactForm-inputWrapper ContactForm-inputWrapper--subject">
    <input class="ContactForm-inputText" placeholder="" type="text" name="subject"
        v-validate="'required'"
        data-vv-as="Betreff"
        :class="{'ContactForm-inputText--error': hasError('subject')}"
        v-model="form.subject"
      >

      <div class="ContactForm-label" :class="{'ContactForm-label--filled': isFilled(form.subject) }">Betreff</div>
      <div v-show="hasError('subject')" class="ContactForm-error" v-cloak>{{ errors.first('subject') }}</div>
    </div>

    <div class="ContactForm-inputWrapper ContactForm-inputWrapper--message">
      <textarea name="message" class="ContactForm-textarea" name="message" placeholder=""
        v-validate="'required'"
        data-vv-as="Nachricht"
        :class="{'ContactForm-textarea--error': hasError('message')}"
        v-model="form.message"
      ></textarea>

      <div class="ContactForm-label" :class="{'ContactForm-label--filled': isFilled(form.message) }">Nachricht</div>
      <div v-show="hasError('message')" class="ContactForm-error" v-cloak>{{ errors.first('message') }}</div>
    </div>

    <div class="ContactForm-note defaultText defaultText--small">
      Mit dem Absenden des Kontaktformulars erklären Sie sich damit einverstanden, dass wir die angegebenen Daten zur Bearbeitung Ihrer Anfrage verarbeiten können. <a href="<?=$pages->get(1064)->url;?>">Datenschutzerklärung</a>
    </div>

    <div class="ContactForm-messageAndSend">
      <div class="ContactForm-message ContactForm-message--success" v-if="status === 'success'" v-cloak>
        Übermittlung erfolgreich. Vielen dank für Ihre Nachricht.
      </div>
      <div class="ContactForm-message ContactForm-message--error" v-if="status=== 'error'" v-cloak>
        Leider gab es einen Fehler. Bitte versuchen Sie es später erneut oder setzen Sie sich auf anderem Wege mit uns in Verbindung. {{ errorMessage }}
      </div>
      <button class="defaultButton" type="submit" @click.prevent="validateAndSubmit" :disabled="sending">Senden</button>
    </div>

  </form>

</div>

<form class="contactForm" action="/api/contactForm" method="POST">

  <div class="contactForm-inputWrapper -name">
    <input class="contactForm-inputText" placeholder="Name" type="text" name="name"
      v-validate="'required'"
      data-vv-as="Name"
      :class="{'-error': errors.has('name')}"
      v-model="form.name"
    >
    <div v-show="errors.has('name')" class="contactForm-error">{{ errors.first('name') }}</div>
  </div>

  <div class="contactForm-inputWrapper -email">
    <input class="contactForm-inputText" placeholder="Email" type="text" name="email"
      v-validate="'required|email'"
      data-vv-as="E-Mail"
      :class="{'-error': errors.has('email')}"
      v-model="form.email"
    >
    <div v-show="errors.has('email')" class="contactForm-error">{{ errors.first('email') }}</div>
  </div>

  <div class="contactForm-inputWrapper -subject">
  <input class="contactForm-inputText" placeholder="Betreff" type="text" name="subject"
      v-validate="'required'"
      data-vv-as="Betreff"
      :class="{'-error': errors.has('subject')}"
      v-model="form.subject"
    >
    <div v-show="errors.has('subject')" class="contactForm-error">{{ errors.first('subject') }}</div>
  </div>

  <div class="contactForm-inputWrapper -message">
    <textarea name="message" class="contactForm-textarea" name="message" placeholder="Nachricht…"
      v-validate="'required'"
      data-vv-as="Nachricht"
      :class="{'-error': errors.has('message')}"
      v-model="form.message"
    ></textarea>
    <div v-show="errors.has('message')" class="contactForm-error">{{ errors.first('message') }}</div>
  </div>

  <div class="contactForm-messageAndSend">
    <div class="contactForm-message -success" v-if="status === 'success'">
      Übermittlung erfolgreich. Vielen dank für Ihre Nachricht.
    </div>
    <div class="contactForm-message -error" v-if="status=== 'error'">
      Leider gab es einen Fehler. Bitte versuchen Sie es später erneut oder setzen Sie sich auf anderem Wege mit uns in Verbindung. {{ errorMessage }}
    </div>
    <button class="contactForm-button" type="submit" @click.prevent="validateAndSubmit" :disabled="sending">Senden</button>
  </div>

</form>

<form class="contactForm" action="/api/contactForm" method="POST">
  <label class="contactForm-label" for="email">E-Mail</label>
  <input class="contactForm-inputText" type="text" name="email" v-validate="'required|email'" data-vv-as="E-Mail" :classes="classes" value="thomas.aull@gmx.de"/>
  <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>

  <label class="contactForm-label" for="name">Name</label>
  <input class="contactForm-inputText" type="text" name="name" value="jo"/>

  <label class="contactForm-label" for="name">Nachricht</label>
  <textarea name="message" class="contactForm-textarea">Messsssage</textarea>

  <button class="defaultButton" type="submit">Send</button>
</form>

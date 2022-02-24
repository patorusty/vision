<template>
  <v-container
    id="login"
    class="fill-height justify-center"
    tag="section"
  >
    <v-row justify="center">
      <v-slide-y-transition appear>
        <base-material-card
          color="primary"
          light
          max-width="100%"
          width="400"
          class="px-5 py-3"
        >
          <template v-slot:heading>
            <div class="text-center">
              <h1 class="display-2 font-weight-bold mb-2">
                Login
              </h1>

              <!-- <v-btn
                v-for="(social, i) in socials"
                :key="i"
                :href="social.href"
                class="ma-1"
                icon
                rel="noopener"
                target="_blank"
              >
                <v-icon v-text="social.icon" />
              </v-btn> -->
            </div>
          </template>

          <v-card-text class="text-center">
            <!-- <div class="text-center grey--text body-1 font-weight-light">
              Or Be Classical
            </div> -->

            <!-- <v-text-field
              color="secondary"
              label="First Name..."
              prepend-icon="mdi-face"
              class="mt-10"
            /> -->
            <v-form ref="form">
              <v-text-field
                color="primary"
                label="Email..."
                prepend-icon="mdi-email"
                :rules="[
                  (v) => !!v || 'Email es un campo obligatorio',
                  !emailUsado || 'Este email no existe en la base de datos',
                ]"
                name="email"
                v-model="email"
                :error="badCredentials"
                @keydown="checkEmail"
              />

              <v-text-field
                class="mb-8"
                color="primary"
                label="Password..."
                prepend-icon="mdi-lock-outline"
                :rules="[
                  (v) => !!v || 'Password es un campo obligatorio',
                  !badCredentials || 'El email o password es incorrecto',
                ]"
                name="password"
                type="password"
                v-model="password"
                @keydown="badCredentials = false"
              />

              <v-checkbox
                color="primary"
                v-model="remember"
                label="Recordarme"
              ></v-checkbox>
            </v-form>
            <pages-btn
              large
              color=""
              depressed
              @click=login
              class="v-btn--text success--text"
            >
              Let's Go
            </pages-btn>
          </v-card-text>
        </base-material-card>
      </v-slide-y-transition>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";
import { debounce } from "debounce";
import { helpers } from "../../helpers";
import http from "../../http-request";

axios.defaults.withCredentials = true;
// axios.defaults.baseURL = process.env.VUE_APP_ROOT_API;

export default {
  name: "PagesLogin",
  mixins: [helpers],
  components: {
    PagesBtn: () => import("./components/Btn")
  },

  data: () => ({
    email: "",
    password: "",
    badCredentials: false,
    emailUsado: false,
    remember: true
  }),
  methods: {
    login() {
      if (this.$refs.form.validate()) {
        this.getCookie().then(() => {
          http
            .post("/login", {
              email: this.email,
              password: this.password,
              remember: this.remember
            })
            .then(r => {
              localStorage.setItem("isLoggedIn", "true");
              this.$router.push({ name: "Polizas" });
            })
            .catch(error => {
              if (error.response.data.errors.email) {
                this.badCredentials = true;
              }
            });
        });
      }
    },
    checkEmail: debounce(async function() {
      this.getCookie().then(async () => {
        if (this.email.length >= 4) {
          const resp = await http.post("/usuario/busquedaMail", {
            email: this.email
          });
          if (resp.data.usado === true) {
            this.emailUsado = false;
          } else {
            this.emailUsado = true;
          }
        }
      });
    }, 2000)
  }
};
</script>

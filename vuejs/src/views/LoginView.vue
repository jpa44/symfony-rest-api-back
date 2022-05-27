<template>
  <v-app>
    <v-main>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-toolbar dark color="primary">
                <v-toolbar-title>{{ isRegister ? stateObj.register.name : stateObj.login.name }} form</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <form ref="form" @submit.prevent="isRegister ? register() : login()">
                  <v-text-field v-if="isRegister"
                                v-model="username"
                                name="username"
                                label="Username"
                                type="username"
                                placeholder="username"
                                required
                  ></v-text-field>

                  <v-text-field
                      v-model="email"
                      name="email"
                      label="Email"
                      type="text"
                      placeholder="email"
                      required
                  ></v-text-field>

                  <v-text-field
                      v-model="password"
                      name="password"
                      label="Password"
                      type="password"
                      placeholder="password"
                      required
                  ></v-text-field>

                  <v-text-field v-if="isRegister"
                                v-model="confirmPassword"
                                name="confirmPassword"
                                label="Confirm Password"
                                type="password"
                                placeholder="confirm password"
                                required
                  ></v-text-field>

                  <div class="red--text"> {{ errorMessage }}</div>
                  <v-btn type="submit" class="mt-4" color="primary" value="log in">
                    {{ isRegister ? stateObj.register.name : stateObj.login.name }}
                  </v-btn>
                  <div class="grey--text mt-4" v-on:click="isRegister = !isRegister;">
                    {{ toggleMessage }}
                  </div>
                </form>
              </v-card-text>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import {mapActions} from "vuex";

export default {
  name: "LoginView",
  data() {
    return {
      username: "",
      email: "",
      password: "",
      confirmPassword: "",
      isRegister: false,
      errorMessage: "",
      stateObj: {
        register: {
          name: 'Register',
          message: 'Aleady have an Account? login.'
        },
        login: {
          name: 'Login',
          message: 'Register'
        }
      }
    };
  },
  methods: {
    ...mapActions(["LogIn"]),
    ...mapActions(["Register"]),
    async login() {
      this.errorMessage = "";
      try {
        await this.LogIn({
          email: this.email,
          password: this.password
        });

        await this.$router.push({name: "dashboard"});
      } catch (error) {
        this.errorMessage = "Invalid username or password";
      }
    },
    async register() {
      if (this.password == this.confirmPassword) {
        this.errorMessage = "";

        try {
          await this.Register({
            firstName: this.username,
            email: this.email,
            password: this.password
          });

          this.isRegister = false;
        } catch (error) {
          this.errorMessage = "";
        }


      } else {
        this.errorMessage = "password did not match"
      }
    }
  },
  computed: {
    toggleMessage: function () {
      return this.isRegister ? this.stateObj.register.message : this.stateObj.login.message
    }
  }
};
</script>
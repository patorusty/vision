<template>
  <v-card ref="modal">
    <v-card-title>
      <span>Crear Riesgo: {{riesgo.nombre}}</span>
    </v-card-title>
    <v-card-text>
      <transition-group
        name="smooth"
        tag="div"
      >
        <div
          :key="1"
          v-if="length >= 1"
        >
          Detalle y Suma 1
          <v-textarea
            v-model="otro_riesgo.detalle_1"
            label="Detalle"
          ></v-textarea>
          <v-text-field
            v-model="otro_riesgo.suma_1"
            label="
        Suma"
          >
          </v-text-field>
        </div>
        <div
          :key="2"
          v-if="length > 1"
        >
          Detalle y Suma 2
          <v-textarea
            v-model="otro_riesgo.detalle_2"
            label="Detalle"
          ></v-textarea>
          <v-text-field
            v-model="otro_riesgo.suma_2"
            label="
        Suma"
          >
          </v-text-field>
        </div>
        <div
          :key="3"
          v-if="length > 2"
        >
          Detalle y Suma 3
          <v-textarea
            v-model="otro_riesgo.detalle_3"
            label="Detalle"
          ></v-textarea>
          <v-text-field
            v-model="otro_riesgo.suma_3"
            label="
        Suma"
          >
          </v-text-field>
        </div>
        <div
          :key="4"
          v-if="length > 3"
        >
          Detalle y Suma 4
          <v-textarea
            v-model="otro_riesgo.detalle_4"
            label="Detalle"
          ></v-textarea>
          <v-text-field
            v-model="otro_riesgo.suma_4"
            label="
        Suma"
          >
          </v-text-field>
        </div>
        <div
          :key="5"
          v-if="length > 4"
        >
          Detalle y Suma 5
          <v-textarea
            v-model="otro_riesgo.detalle_5"
            label="Detalle"
          ></v-textarea>
          <v-text-field
            v-model="otro_riesgo.suma_5"
            label="
        Suma"
          >
          </v-text-field>
        </div>
        <div
          :key="6"
          v-if="length > 5"
        >
          Detalle y Suma 6
          <v-textarea
            v-model="otro_riesgo.detalle_6"
            label="Detalle"
          ></v-textarea>
          <v-text-field
            v-model="otro_riesgo.suma_6"
            label="
        Suma"
          >
          </v-text-field>
        </div>
        <div
          :key="7"
          v-if="length > 6"
        >
          Detalle y Suma 7
          <v-textarea
            v-model="otro_riesgo.detalle_7"
            label="Detalle"
          ></v-textarea>
          <v-text-field
            v-model="otro_riesgo.suma_7"
            label="
        Suma"
          >
          </v-text-field>
        </div>
        <div
          :key="8"
          v-if="length > 7"
        >
          Detalle y Suma 8
          <v-textarea
            v-model="otro_riesgo.detalle_8"
            label="Detalle"
          ></v-textarea>
          <v-text-field
            v-model="otro_riesgo.suma_8"
            label="
        Suma"
          >
          </v-text-field>
        </div>
        <div
          :key="9"
          v-if="length > 8"
        >
          Detalle y Suma 9
          <v-textarea
            v-model="otro_riesgo.detalle_9"
            label="Detalle"
          ></v-textarea>
          <v-text-field
            v-model="otro_riesgo.suma_9"
            label="
        Suma"
          >
          </v-text-field>
        </div>
        <div
          :key="10"
          v-if="length > 9"
        >
          Detalle y Suma 10
          <v-textarea
            v-model="otro_riesgo.detalle_10"
            label="Detalle"
          ></v-textarea>
          <v-text-field
            v-model="otro_riesgo.suma_10"
            label="
        Suma"
          >
          </v-text-field>
        </div>
      </transition-group>
    </v-card-text>
    <v-card-text>
      <v-btn
        icon
        small
        color="primary"
        dark
        @click="removeDetalle"
        :disabled="length > 10"
        class="mr-2"
      >
        <v-icon>
          mdi-minus
        </v-icon>
      </v-btn>
      <v-btn
        icon
        small
        color="primary"
        dark
        @click="addDetalle"
        :disabled="length > 10"
      >
        <v-icon>
          mdi-plus
        </v-icon>
      </v-btn>
    </v-card-text>
    <v-card-actions class="d-flex justify-end">
      <v-btn
        class="mb-2"
        color="red"
        text
        @click="close"
      >Cancelar</v-btn>
      <v-btn
        class="mb-2"
        color="green"
        @click="create"
        text
        native-type="submit"
      >Crear</v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
import { mapState, mapMutations } from "vuex";
export default {
  data: () => ({
    tipo_riesgo_id: null,
    length: 1,
    tab: null
  }),
  computed: {
    ...mapState("poliza", ["tipo_riesgos", "riesgo"]),
    ...mapState("modal", ["modal_or"]),
    ...mapState("riesgo", ["otro_riesgo"]),
    detalle(n) {
      return `otro_riesgo.detalle_${n}`;
    }
  },
  methods: {
    ...mapMutations("modal", ["HIDE_MODAL_OR"]),
    create() {},
    close() {},
    addDetalle() {
      if (this.length < 10) {
        this.length++;
        this.scrollToEnd();
      }
    },
    removeDetalle() {
      if (this.length > 1) {
        this.length--;
      }
    },
    scrollToEnd() {
      const modal = this.$refs.modal;
      console.log(modal.scrollHeight);
      modal.$el.scrollIntoView({
        alignToTop: false,
        block: "end",
        behavior: "smooth"
      });
      console.log(modal.$el);
    }
  },
  watch: {
    length(val) {
      this.tab = val - 1;
    }
  }
};
</script>

<style lang="scss" scoped>
.smooth-enter-active,
.smooth-leave-active {
  transition: opacity 0.5s;
}
.smooth-enter,
.smooth-leave-to {
  opacity: 0;
}
</style>
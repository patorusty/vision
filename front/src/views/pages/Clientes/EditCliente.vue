<template>
  <v-card class="mt-0 mx-4 pa-3">
    <v-form ref="form">
      <v-container>
        <v-card-title> Datos Personales </v-card-title>
        <v-card-text>
          <v-row>
            <v-col
              class="pb-0"
              :cols="3"
            >
              <v-select
                :rules="[rules.required]"
                v-model="cliente.tipo_persona"
                :items="tipos_persona"
                item-text="text"
                item-value="value"
                label="Tipo de Persona"
              >
              </v-select>
            </v-col>
          </v-row>
          <v-row>
            <v-col
              class="pt-0 pb-0"
              v-if="cliente.tipo_persona === 2"
            >
              <v-text-field
                v-model="cliente.razon_social"
                :rules="
                  cliente.tipo_persona === 2
                    ? [rules.required]
                    : null
                "
                label="Razon Social"
                v-uppercase
              >
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-model="cliente.nombre"
                :rules="[rules.required]"
                label="Nombre"
                v-uppercase
              ></v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-model="cliente.apellido"
                :rules="[rules.required]"
                label="Apellido"
                v-uppercase
              ></v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-model="cliente.nro_dni"
                :rules="[
                  rules.required,
                  !dniUsado || 'Este DNI ya está en uso',
                ]"
                label="DNI"
                @keyup="buscarDNI"
                v-uppercase
              ></v-text-field>
            </v-col>
            <v-col
              class="pt-0 pb-0"
              v-if="cliente.tipo_persona === 2"
            >
              <v-text-field
                v-model="cliente.cuit"
                :rules="
                  cliente.tipo_persona === 2
                    ? [rules.required, !cuitUsado || 'Este CUIT ya está en uso']
                    : null
                "
                @keyup="buscarCuit"
                label="CUIT"
                v-uppercase
              >
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-select
                :items="condiciones"
                :rules="[rules.required]"
                item-text="text"
                item-value="value"
                label="Condición Fiscal"
                v-model="cliente.condicion_fiscal"
              >
              </v-select>
            </v-col>

            <v-col class="pt-0 pb-0">
              <v-menu
                v-model="calendario"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    :rules="[rules.required]"
                    clearable
                    @click:clear="$nextTick(() => (cliente.nacimiento = null))"
                    v-model="fechaFormateada"
                    label="Nacimiento"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  clearable
                  v-model="cliente.nacimiento"
                  color="primary lighten-1"
                  no-title
                  scrollable
                  locale="es-la"
                  @input="calendario = false"
                >
                  <v-btn
                    text
                    color="primary"
                    @click="menu = false"
                  >Cancel</v-btn>
                  <v-btn
                    text
                    color="primary"
                  >OK</v-btn>
                </v-date-picker>
              </v-menu>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-radio-group
                :rules="[rules.required]"
                v-model="cliente.sexo"
                row
              >
                <v-radio
                  label="Masculino"
                  value="M"
                ></v-radio>
                <v-radio
                  label="Femenino"
                  value="F"
                ></v-radio>
              </v-radio-group>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-model="cliente.email"
                :rules="[rules.required, rules.email]"
                label="E-mail"
                v-uppercase
              ></v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-text-field
                v-model="cliente.email_alt"
                label="E-mail Alt."
                v-uppercase
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-textarea
                rows="1"
                auto-grow
                label="Observaciones"
                v-model="cliente.observaciones_1"
              ></v-textarea>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-title> Dirección </v-card-title>
        <v-card-text>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                label="Calle"
                v-model="cliente.direccion"
                :rules="[rules.required]"
              >
              </v-text-field>
            </v-col>
            <v-col
              :cols="3"
              class="pt-0 pb-0"
            >
              <v-text-field
                label="Número"
                v-model="cliente.direccion_nro"
                :rules="[rules.required]"
              >
              </v-text-field>
            </v-col>
            <v-col
              :cols="1"
              class="pt-0 pb-0"
            >
              <v-text-field
                label="Piso"
                v-model="cliente.direccion_piso"
              >
              </v-text-field>
            </v-col>
            <v-col
              :cols="1"
              class="pt-0 pb-0"
            >
              <v-text-field
                label="Depto"
                v-model="cliente.direccion_depto"
              >
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                label="Barrio / Country"
                v-model="cliente.barrio_cerrado"
              >
              </v-text-field>
            </v-col>
            <v-col
              :cols="2"
              class="pt-0 pb-0"
            >
              <v-text-field
                label="Lote"
                v-model="cliente.lote"
              > </v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-autocomplete
                :rules="[rules.required]"
                :items="localidades"
                :item-text="localidadesText"
                item-value="id"
                v-model="cliente.localidad_id"
                label="Localidad"
              ></v-autocomplete>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-title> Teléfonos </v-card-title>
        <v-card-text>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-text-field
                label="Celular"
                v-model="cliente.celular"
                :rules="[rules.required]"
              >
              </v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-text-field
                label="Teléfono"
                v-model="cliente.telefono_1"
              >
              </v-text-field>
            </v-col>
            <v-col class="pt-0 pb-0">
              <v-text-field
                label="Teléfono Alternativo"
                v-model="cliente.telefono_2"
              >
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col class="pt-0 pb-0">
              <v-textarea
                rows="1"
                auto-grow
                label="Observaciones"
                v-model="cliente.observaciones_2"
              ></v-textarea>
            </v-col>
          </v-row>
        </v-card-text>
        <div class="d-flex flex-row">
          <div style="width: 30%">
            <v-card-title> Productor </v-card-title>
            <v-card-text>
              <v-row>
                <v-col class="pt-0 pb-0">
                  <v-select
                    label="Seleccione Productor"
                    v-model="cliente.productor_id"
                    :items="productores"
                    :item-text="codigoText"
                    item-value="id"
                    :rules="[rules.required]"
                  >
                  </v-select>
                </v-col>
              </v-row>
            </v-card-text>
          </div>
          <div style="width: 70%">
            <v-card-title> Registro </v-card-title>
            <v-card-text>
              <v-row>
                <v-col class="pt-0 pb-0">
                  <v-text-field
                    label="Registro"
                    v-model="cliente.registro"
                  >
                  </v-text-field>
                </v-col>
                <v-col class="pt-0 pb-0">
                  <v-menu
                    v-model="calendario2"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        clearable
                        @click:clear="
                          $nextTick(() => (cliente.vencimiento_registro = null))
                        "
                        :value="dateToString(cliente.vencimiento_registro)"
                        label="Fecha de Vencimiento"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      clearable
                      v-model="cliente.vencimiento_registro"
                      color="primary lighten-1"
                      no-title
                      scrollable
                      locale="es-la"
                      @input="calendario2 = false"
                    >
                      <!-- <v-btn text color="primary" @click="menu = false"
                        >Cancel</v-btn
                      >
                      <v-btn text color="primary">OK</v-btn> -->
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <!-- <v-col>
                  <v-file-input
                    accept="image/*"
                    @change="filesSelected"
                    v-model="files"
                    placeholder="Elija una o varias fotos..."
                    label="Imágen del registro"
                    prepend-icon="mdi-camera"
                    multiple
                    :loading="isLoading"
                  >
                    <template v-slot:selection="{ text }">
                      <v-chip small label color="primary">
                        {{ text }}
                      </v-chip>
                    </template>
                  </v-file-input>
                  <v-btn
                    @click="upload"
                    color="primary"
                    :disabled="images.length < 1"
                  >
                    Subir
                  </v-btn>
                </v-col> -->
              </v-row>
            </v-card-text>
          </div>
        </div>
        <v-card-actions class="d-flex justify-end">
          <v-btn
            class="mb-2"
            color="red"
            text
            @click="volver"
          >Volver</v-btn>
          <v-btn
            class="mb-2"
            color="green"
            @click="udpate"
            text
            native-type="submit"
          >Guardar</v-btn>
        </v-card-actions>
      </v-container>
    </v-form>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import http from "../../../http-request";
import { helpers } from "../../../helpers";
import { debounce } from "debounce";
export default {
  mixins: [helpers],
  data: () => ({
    rules: {
      required: value => !!value || "Este campo obligatorio",
      email: value => {
        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern.test(value) || "Igrese un email válido";
      }
    },
    localidades: [],
    cuitUsado: false,
    dniUsado: false,
    calendario: false,
    calendario2: false,
    isLoading: false,
    images: [],
    files: []
  }),
  computed: {
    ...mapState("cliente", ["cliente", "tipos_persona", "condiciones"]),
    ...mapState("productor", ["productores"])
  },
  methods: {
    ...mapActions("cliente", ["getCliente", "updateCliente", "getClientes"]),
    ...mapMutations("cliente", ["RESET_CLIENTE"]),
    ...mapActions("productor", ["getProductoresActivos"]),

    buscarCuit: debounce(async function() {
      if (
        this.cliente.cuit.length >= 6 &&
        this.cliente.cuit != this.cliente.cuitOriginal
      ) {
        const resp = await http.post("/clientes/busquedaCuit", {
          cuit: this.cliente.cuit
        });
        this.cuitUsado = resp.data.usado;
      }
    }, 700),
    buscarDNI: debounce(async function() {
      if (
        this.cliente.nro_dni.length >= 4 &&
        this.cliente.nro_dni != this.cliente.dniOriginal
      ) {
        const resp = await http.post("/clientes/busquedaDNI", {
          dni: this.cliente.nro_dni
        });
        this.dniUsado = resp.data.usado;
      }
    }, 700),
    filesSelected() {},
    upload() {},
    volver() {
      this.$router.push({
        name: "Clientes"
      });
      this.RESET_CLIENTE();
    },
    async udpate() {
      if (this.$refs.form.validate()) {
        const udpateResult = await this.updateCliente(this.cliente);
        if (udpateResult) {
          this.$router.push({
            name: "Clientes"
          });
          this.RESET_CLIENTE();
        }
      }
    }
  },
  created() {
    this.getCliente(this.$route.params.id);
    this.getClientes();
    this.cargarLocalidades();
    this.getProductoresActivos();
  },
  beforeDestroy() {
    this.RESET_CLIENTE();
  }
};
</script>

<style>
</style>
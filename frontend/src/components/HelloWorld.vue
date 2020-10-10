<template>
  <div class="hello">
    <div class="row">
      <div class="col-md-8">
        <h3>List Of Vehicles</h3>
        <b-table show-empty striped hover filter-debounce="" :items="list.data" :fields="fields" class="mt-4">
          <template v-slot:cell(price)="data">
            $ {{data.value}}
          </template>
          <template v-slot:cell(bore)="data">
            {{data.value}} {{data.item.bore_unit_name}}
          </template>
          <template v-slot:cell(stroke)="data">
            {{data.value}} {{data.item.stroke_unit_name}}
          </template>
          <template v-slot:cell(engine_displacement)="data">
            {{data.value}} {{data.item.displacement_unit_name}}
          </template>
        </b-table>
      </div>
      <div class="col-md-4">
        <h3>Insert Vehicle</h3>
        <b-form class="mt-4" @submit="save" @reset="onReset">
          <b-form-group id="input-group-2" label="Vehicle Name:" label-for="input-2">
            <b-form-input
              id="input-2"
              v-model="form.name"
              required
              placeholder="Enter name"
            ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-3" label="Vehicle Location:" label-for="input-2">
            <b-form-input
              id="input-3"
              v-model="form.location"
              required
              placeholder="Enter Location"
            ></b-form-input>
          </b-form-group>
          <b-form-group id="input-group-3" label="Vehicle Price:" label-for="input-2">
            <b-form-input
              id="input-3"
              number
              v-model="form.price"
              required
              placeholder="Enter Price"
            ></b-form-input>
          </b-form-group>
          <b-form-group id="input-group-3" label="Vehicle Engine Power:" label-for="input-2">
            <b-form-input
              id="input-3"
              number
              v-model="form.engine_power"
              required
              placeholder="Enter Power"
            ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-4" label="Displacement Unit" label-for="input-2">
            <b-form-select
              @change="calculateDisplacement"
              id="input-group-4"
              class="mb-2 mr-sm-2 mb-sm-0"
              required
              v-model="form.displacement_unit"
              :options="[{ text: 'Cubic Centimeters', value: 1 }, { text: 'Cubic Inches', value: 2 }]"
              :value="null"
            ></b-form-select>
          </b-form-group>
          <b-form-group id="input-group-5" label="Bore" label-for="input-2">
            <b-input-group :append="form.displacement_unit == 1 ? 'Millimeter' : 'Inches'">
              <b-form-input @change="calculateDisplacement" number v-model="form.bore" required></b-form-input>
            </b-input-group>
          </b-form-group>
          <b-form-group id="input-group-6" label="Stroke" label-for="input-2">
            <b-input-group :append="form.displacement_unit == 1 ? 'Millimeter' : 'Inches'">
              <b-form-input @change="calculateDisplacement" number v-model="form.stroke" required></b-form-input>
            </b-input-group>
          </b-form-group>
          <b-form-group id="input-group-7" label="Cylinders" label-for="input-2">
            <b-form-input
              id="input-2"
              number
              @change="calculateDisplacement"
              v-model="form.cylinders"
              required
              placeholder="Enter Cylinders"
            ></b-form-input>
          </b-form-group>
          <b-form-group id="input-group-7" label="Engine Displacement" label-for="input-2">
            <b-form-input
              disabled
              @change="calculateDisplacement"
              v-model="form.engine_displacement"
            ></b-form-input>
          </b-form-group>

          <b-button type="submit" variant="primary">Submit</b-button>
          <b-button type="reset" variant="danger">Reset</b-button>
        </b-form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HelloWorld',
  data () {
    return {
      form: {
        name: '',
        location: '',
        bore: 0,
        stroke: 0,
        cylinders: 1,
        engine_displacement: 0,
        engine_power: 0,
        price: 0,
        displacement_unit: 1,
        _saving: false
      },
      fields: [
        {
          label:'Vehicle Name',
          key: 'name',
          sortable: true
        },
        {
          label:'Vehicle Location',
          key: 'name',
          sortable: true
        },
        {
          label:'Price',
          key: 'price',
          sortable: true
        },
        {
          label:'Bore',
          key: 'bore',
          sortable: true
        },
        {
          label:'Stroke',
          key: 'stroke',
          sortable: true
        },
        {
          label:'Cylinders',
          key: 'cylinders',
          sortable: true
        },
        {
          label:'Engine Displacement',
          key: 'engine_displacement',
          sortable: true
        },
      ],
      list: {
        data: [],
        loading: false,
      }
    }
  },

  methods: {
    calculateDisplacement()
    {
      if(this.form.displacement_unit == 1)
        this.form.engine_displacement = (Math.pow(parseInt(this.form.bore), 2) * 0.7854 * parseInt(this.form.stroke) * 0.001 * parseInt(this.form.cylinders)).toFixed(2)
      else if(this.form.displacement_unit == 2)
        this.form.engine_displacement = (Math.pow(parseInt(this.form.bore), 2) * 0.7854 * parseInt(this.form.stroke) * parseInt(this.form.cylinders)).toFixed(2)
    },

    onReset(evt) {
      evt.preventDefault()
      // Reset our form values
      this.form._saving = false
      this.form.name = ''
      this.form.location = ''
      this.form.bore = 0
      this.form.stroke = 0
      this.form.cylinders = 1
      this.form.engine_displacement = 0
      this.form.engine_power = 0
      this.form.price = 0
      this.form.displacement_unit = 1
    },
    getData()
    {
      this.list.loading = true

      axios.get(BASE_API + 'vehicle').then(response =>
      {
        let data = response.data.response.items
        this.list.data = data
        this.list.loading = false
      }).catch(error => {
        this.list.loading = false
      })
    },
    save(evt)
    {
      evt.preventDefault()

      this.form._saving = true

      axios.post(BASE_API + 'vehicle/save', {
        name: this.form.name,
        location: this.form.location,
        bore: this.form.bore,
        stroke: this.form.stroke,
        cylinders: this.form.cylinders,
        engine_displacement: this.form.engine_displacement,
        engine_power: this.form.engine_power,
        price: this.form.price,
        displacement_unit: this.form.displacement_unit,
      }).then(response =>
      {
        this.getData()
        this.onReset()
        this.form._saving = false
      }).catch(error => {
        this.form._saving = false
      })
    }
  },

  mounted() {
    this.getData()
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>

import Vue from 'vue'
import HelloWorld from '@/components/HelloWorld'

describe('HelloWorld.vue', () => {
  it('should render correct contents', () => {
    const Constructor = Vue.extend(HelloWorld)
    const vm = new Constructor().$mount()

    expect(vm.$el.querySelector('.hello #titleOfList').textContent)
      .toEqual('List Of Vehicles')

    expect(vm.$el.querySelector('.hello #titleOfForm').textContent)
      .toEqual('Insert Vehicle')

    expect(vm.$el.querySelector('.hello #insertVehicleForm').textContent)
  })
})

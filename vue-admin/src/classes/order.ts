import {Entity} from "@/interfaces/entity";
import {OrderItem} from "@/classes/order_item";

export class Order implements Entity {
    id:number
    first_name: string
    last_name: string
    email: string
    total: number
    order_items: OrderItem[]

    constructor(id: number = 0, first_name: string = '', last_name: string = '', email: string = '', total: number = 0, order_items:any[] = []) {
        this.id = id
        this.first_name = first_name
        this.last_name = last_name
        this.email = email
        this.total = total
        this.order_items = order_items
    }


}

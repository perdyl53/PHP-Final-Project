select orders.orderid, clients.clientid, clients.fname from clients inner join orders on clients.clientid=orders.clientid
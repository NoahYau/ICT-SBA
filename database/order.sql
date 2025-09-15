create table customer_order (
    id varchar(16) not null,
    create_date datetime not null,
    primary key (id)
);

create table order_item (
    order_id varchar(16) not null,
    product_id int not null,
    name varchar(32) not null,
    price int not null,
    foreign key (order_id) references customer_order(id)
);
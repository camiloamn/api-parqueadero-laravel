/*crea la base de datos comfamiliar si no existe */
/*esos pk, pf tienes que llamarlos igual*/
    create dataBase if not exists comfamiliar;
    use comfamiliar;

    create table usuarios(
    id varchar(100) not null,
    nombre varchar(50) not null,/*not null: que no puede ser nulo*/
    apellidos varchar(50) not null,
    email varchar(80) null,
    password varchar(250),
    created_at datetime default null,
    updated_at datetime default null,
    remember_token varchar(255),
    constraint pk_usuarios primary key(id) /*CONSTRAINT= RESTRICCION*/
    )ENGINE=InnoDb; /*ENGINE =MOTOR -> mantenga la integridad
    y se pueda hacer la relacion con el resto de tablas, va obligatorio*/

    create table vehiculos(
    id int auto_increment not null,
    claseVehiculo varchar(30) not null,
    created_at datetime default null,
    updated_at datetime default null,
    id_usuarios varchar(20) not null,
    constraint pk_vehiculos primary key(id),/*restricciones a la allave primaria , mejor dicho dice que es la lllave primaria de esa tabla*/
    /*dice que va a llamar el id de usuario en la tabla vehiculos*/
    constraint fk_vehiculo_usuario foreign key(id_usuarios) references usuarios(id)/*RELACION TABLAS*/ 
    )ENGINE=InnoDb;

    create table tipoVehiculos(
    codigo int auto_increment not null,
    nombre varchar(50) not null,    
    placa varchar(10) not null,
    created_at datetime default null,
    updated_at datetime default null,
    id_vehiculos int not null,
    constraint pk_tipoVehiculos primary key(codigo),     
    constraint fk_tipoVehiculo_vehiculos foreign key(id_vehiculos) references vehiculos(id)/*RELACION TABLAS*/ 
    )ENGINE=InnoDb;/*siempre va al final de las tablas*/

    create table tDocumentos(
    id int auto_increment not null,
    tipoDocumento varchar(100),
    id_usuarios varchar(20) not null,
    constraint pk_tDocumentos primary key(id),
    constraint fk_tDocumento_usuario foreign key(id_usuarios) references usuarios(id)
    )ENGINE=InnoDb;

    create table listas(
    id int auto_increment not null,
    fechainicio date not null,
    fechafin date not null,
    url varchar (100) not null,
    /*id_vehiculos int not null,*/
    codigoTipoVehiculo int not null,
    id_tDocumentos int not null,
    constraint pk_listas primary key(id),
    /* constraint fk_lista_vehiculos foreign key(id_vehiculos) references vehiculos(id), */
    constraint fk_lista_tipoVehiculos foreign key(codigoTipoVehiculo) references tipoVehiculos(codigo),
    constraint fk_lista_tDocumentos foreign key(id_tDocumentos) references tDocumentos(id)
    )ENGINE=InnoDb;

/*constraint fk_listas_idVehiculo_vehiculos
constraint fk_listas_tipoVehiculos foreign key(codigoTipoVehiculo) references tipoVehiculos(codigo),
constraint fk_listas_vehiculos foreign key(id_Vehiculos) references Vehiculos(id)*/

create table docVehiculos(
id int auto_increment not null,
/*tipoVehiculo varchar(30) not null,*/
tarjetaDePropiedad varchar(100),
datosVehiculo varchar(100),
soat varchar(100),
tecnomecanico varchar(100),
facturaDeCompra varchar(100),
polizas varchar(100),
formularioDeSolicitud varchar(100),
oficioEspecifiacionesVehiculo varchar(100),
fichaTecnicaHomologacion varchar(100),
declaracionImportacion varchar(100),
tarjetaPropiedad varchar(100),
licenciaConduccion varchar(100),
pagoLicenciaTransito varchar(100),
impuestoRodaje varchar(100),
multasYsanciones varchar(100),
created_at datetime default null,
updated_at datetime default null,
id_vehiculos int not null,
codigoTipoVehiculo int not null,
constraint pk_docVehiculos primary key(id),/*llave primaria*/
/*va allamar de tipo vehiculo el codigo */
constraint fk_docVehiculo_tipoVehiculos foreign key(codigoTipoVehiculo) references tipoVehiculos(codigo),
constraint fk_docVehiculo_vehiculos foreign key(id_vehiculos) references vehiculos(id)
)ENGINE=InnoDb;

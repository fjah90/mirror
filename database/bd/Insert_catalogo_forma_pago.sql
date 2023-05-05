/************Insert_catalogo_forma_pago************/

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('1', '01', 'Efectivo', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('2', '02', 'Cheque nominativo', 'Si', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', '[0-9]{11}|[0-9]{18}', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('3', '03 ', 'Transferencia electrónica de fondos', 'SI', '[0-9]{10}|[0-9]{18}', '[0-9]{10}|[0-9]{16}|[0-9]{18}', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('4', '04 ', 'Tarjeta de crédito', 'SI ', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', '[0-9]{16}', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('5', '05 ', 'Monedero electrónico ', 'SI', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('6', '06', 'Dinero electrónico', 'SI ', 'No', '[0-9]{10}', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('7', '08', 'Vales de despensa ', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('8', '12', 'Dación en pago', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('9', '13', 'Pago por subrogación', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('10', '14 ', 'Pago por consignación', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('11', '15 ', 'Condonación', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('12', '17', 'Compensación ', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('13', '23', 'Novación', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('14', '24 ', 'Confusión ', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('15', '25 ', 'Remisión de deuda', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('16', '26 ', 'Prescripción o caducidad', 'NO', 'No ', 'No ', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('17', '27 ', 'A satisfacción del acreedor', 'NO', 'No ', 'No ', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('18', '28', 'Tarjeta de débito ', 'SI', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50} ', '[0-9]{16}', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('19', '29 ', 'Tarjeta de servicios', 'SI', '[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}', '[0-9]{15,16}', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('20', '30 ', 'Aplicación de anticipos', 'NO', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('21', '99 ', 'Por definir ', 'SI', 'Opcional', 'Opcional', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

INSERT INTO `cat_forma_pago` (`id`, `codigo`, `nombre`, `bancarizado`, `cta_beneficiaria`, `cta_ordenante`, `created_at`, `updated_at`, `deleted_at`) VALUES ('22', '31 ', 'Intermediario pagos ', 'NO ', 'No', 'No', '2023-05-03 00:00:00', '2023-05-03 00:00:00', NULL);

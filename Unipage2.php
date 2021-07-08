<?php

    namespace yandex\translate;

    use yii\helper\Json;
    use yii\helper\Html;

    class Translation
    {
        public $key="AlzalyCf2zgkmk-nRxdbB4gg49M9GZhmFei55u0";

        // API URL

        const DETECT_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/detect';

        //API URL

        const TRANSLATE_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/translate';

        /**
         * Имя класса.
         * @return string наименования класса.
         */
        public static function className()
        {
            return get_called_class();
        }

        public function init()
        {
            parent::init();

            if(empty($this->key))
            {
                throw new InvalidConfigException('Поле <b>$key</b> обязательно к заполнению');
            }
        }

        /** Перевод text/html в $text
         * @param $text Текст который нужно перевести
         * @return mixed array()
        */
        public function detect_text($text)
        {
            // это данные формы, которые будут включены в запрос
            $values = array(
                'key'    => $this->key,
                'text'   => $text
            );

            // преобразовывает массив данных формы в необработанный формат,
            // чтобы его можно было использовать с URL-адресом
            $formData = http_build_query($values);

            // создает соединение с конечной точкой API
            $ch = curl_init(self::DETECT_YA_URL);

            // указывает URL-адресу, чтобы он возвращал ответ, а не выводил его
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // записывает данные формы в запрос в тело сообщения
            curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

            // включить заголовок
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: GET'));

            // выполняет HTTP-запрос
            $json = curl_exec($ch);
            curl_close($ch);

            // декодирует данные ответа
            $data = json_decode($json, true);
            return $data;
        }

        /**
         * @param $text Текст, который надо перевести
         * @param $lang Язык, на который надо перевести
         * @return string
         */
        public function translate_text($text,$lang)
        {
            // это данные формы, которые будут включены в запрос
            $values = array(
                'key'    => $this->key,
                'text'   => $text,
                'lang'   => $lang,
                'format' => 'plain',
            );

            // преобразует массив данных формы в необработанный формат,
            // чтобы его можно было использовать с URL-адресом
            $formData = http_build_query($values);

            // создает соединение с конечной точкой API
            $ch = curl_init(self::TRANSLATE_YA_URL);

            // указать URL-адресу, чтобы он возвращал ответ, а не выводил его
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // записывает данные формы в запрос в теле сообщения
            curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

            // включить заголовок
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: GET'));

            // выполняет HTTP-запрос
            $json = curl_exec($ch);
            curl_close($ch);

            // декодирует данные ответа
            $data = json_decode($json, true);
            if($data['code']==200)
            {
                $text = '';
                foreach($data['text'] as $t)
                {
                    $text.=$t;
                }
                return $text;
            }
            return $data;
        }
    }

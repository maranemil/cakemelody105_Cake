<?php
    /**
     * Error string short short error message.
     */
    define ('SHORT_ERROR_MESSAGE', '<div class="error_message" id="error%s"%s>%s</div>');

    class ValidatorHelper extends Helper {
        //var $validationErrors = array();
        var $helpers = array('Html');

        function setValidationErrors($validationErrors)
        {
          if (count($validationErrors)) {
            $this->validationErrors = $validationErrors;
          }
        }
        function tagErrorMsg($field) {
          //$this->$validationErrors;
          //print_r($this->view->controller->User->__viewClass->validationErrors);
          //print('errors: '); print_r($this->validationErrors); exit;
          $messages = "";
          list($model, $column) = explode('/', $field);
            if(isset($this->validationErrors[$model][$column])) {
              return sprintf(SHORT_ERROR_MESSAGE, $model.Inflector::camelize($column), '',  $this->validationErrors[$model][$column]);
            } else {
              $style = 'style="display: none;"';
              return sprintf(SHORT_ERROR_MESSAGE, $model.Inflector::camelize($column), $style, '');
            }
        }

        function javascriptErrors($modelNames, $javascript=true, $ajax=false) {
          if(!is_array($modelNames)) {
            $modelNames = array($modelNames);
          }

          $scriptTags = "function validate(f) {
                           $('.error_message').hide();
                        ";
          if ($javascript) {
          $scriptTags .= "var validationJSON = {\n";
          foreach($modelNames as $modelName) {
            $model = ClassRegistry::getObject($modelName);

            foreach ($model->validate as $field_name => $validators) {
              $scriptTags .= $model->name.Inflector::camelize($field_name).": [\n";
              $objectBlock = '';
              foreach($validators as $validator) {
                if (array_key_exists('function', $validator)) {
                  continue;
                }
                $objectBlock .= "{'regex': '".substr($validator['expression'], 1, -1)."', 'mesg': '{$validator['message']}'},\n";
              }
              $objectBlock = substr($objectBlock, 0, -2);
              $scriptTags .= $objectBlock . "\n],\n";
            }
          }
          $scriptTags = substr($scriptTags, 0, -2)."};\n";

          $scriptTags .= <<<EOT
              var formEle = f.elements;
              for (ele in formEle) {
                if (!formEle[ele].name || formEle[ele].id == undefined) {
                  continue;
                }
                var elementID = formEle[ele].id;
                if (validationJSON[elementID] != undefined) {
                  for (var i = 0; i < validationJSON[elementID].length; i++) {
                    var v = $('#'+elementID).val();
                    //console.log('VAL: '+v);
                   // console.log('REGEX: '+validationJSON[elementID][i].regex);
                    var reg = new RegExp(validationJSON[elementID][i].regex);
                    //console.log(validationJSON[elementID][i].regex);
                    if (!reg.test(v)) {
                      showErrorMsg(elementID, validationJSON[elementID][i].mesg);
                      alert(validationJSON[elementID][i].mesg);
                      formEle[ele].focus();
                      return false;
                    }
                  }
                }
              }
EOT;
          }
          if ($ajax) {
            //submit form using ajax
            $formValidator = $this->Html->url("validator");
            $scriptTags .= "\nvar data = $('#'+f.id).formSerialize();
                            var responseText = $.ajax( {
                            type: 'POST',
                            url: '$formValidator',
                            data: data,
                            async: false
                            } ).responseText;
                            //console.log(responseText);
                            return processData(responseText);
                            ";
          }

          //Remaining javascript
          $scriptTags .= "}\n";
          $scriptTags .= "
                          function processData(data)
                          {
                            $('.error_message').hide();
                            var jsonObj = eval('(' + data + ')');
                            var error_str = '';
                            if (jsonObj.length == 0) {
                              //no errors
                              return true;
                            } else {
                              for(var i = 0; i < jsonObj.length; i++) {
                                showErrorMsg(jsonObj[i].key, jsonObj[i].value);
                                error_str += jsonObj[i].value+'\\n';
                              }
                              alert('Please correct the following errors: \\n\\n'+error_str);
                              return false;
                            }
                          }\n
                          function showErrorMsg(block_id, mesg)
                          {
                            $('#error'+block_id).html(mesg).show();
                            $('#'+block_id).focus();
                          }
                          ";
          return $scriptTags;
        }
    }
?>

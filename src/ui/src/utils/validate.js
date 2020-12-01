/**
 * 是否合法IP地址
 * @param rule
 * @param value
 * @param callback
 */
export function validateIP(rule, value,callback) {
  if(value==''||value==undefined||value==null){
    return callback();
  }else {
    const reg = /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
    if ((!reg.test(value)) && value != '') {
      return callback(new Error('请输入正确的IP地址'));
    } else {
      return callback();
    }
  }
}

/**
 * 是否手机号码或者固话
 * @param rule
 * @param value
 * @param callback
 */
export function validatePhoneTwo(rule, value, callback) {
  const reg = /^((0\d{2,3}-\d{7,8})|(1[34578]\d{9}))$/;;
  if (value == '' || value == undefined || value == null) {
    return callback();
  } else {
    if ((!reg.test(value)) && value != '') {
      callback(new Error('请输入正确的电话号码或者固话号码'));
    } else {
      return callback();
    }
  }
}

/**
 * 是否固话
 * @param rule
 * @param value
 * @param callback
 */
export function validateTelphone(rule, value,callback) {
  const reg =/0\d{2,3}-\d{7,8}/;
  if(value==''||value==undefined||value==null){
    return callback();
  }else {
    if ((!reg.test(value)) && value != '') {
      return callback(new Error('请输入正确的固定电话）'));
    } else {
      return callback();
    }
  }
}

/**
 * 是否手机号码
 * @param rule
 * @param value
 * @param callback
 */
export function validatePhone(rule, value,callback) {
  const reg =/^[1][3-9][0-9]{9}$/;
  if(value==''||value==undefined||value==null){
    return callback();
  }else {
    if ((!reg.test(value)) && value != '') {
      return callback(new Error('请输入正确的电话号码'));
    } else {
      return callback();
    }
  }
}

/**
 * 是否身份证号码
 * @param rule
 * @param value
 * @param callback
 */
export function validateIdNo(rule, value,callback) {
  const reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
  if(value==''||value==undefined||value==null){
    return callback();
  }else {
    if ((!reg.test(value)) && value != '') {
      return callback(new Error('请输入正确的身份证号码'));
    } else {
      return callback();
    }
  }
}

/**
 * 是否邮箱
 * @param rule
 * @param value
 * @param callback
 */
export function validateEMail(rule, value,callback) {
  const reg =/^([a-zA-Z0-9]+[-_\.]?)+@[a-zA-Z0-9]+\.[a-z]+$/;
  if(value==''||value==undefined||value==null){
    return callback();
  }else{
    if (!reg.test(value)){
      return callback(new Error('请输入正确的邮箱'));
    } else {
      return callback();
    }
  }
}

/**
 * 合法url
 * @param url
 * @returns {boolean}
 */
export function validateURL(url) {
  const urlregex = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/;
  return urlregex.test(url);
}

/**
 * 验证内容是否包含英文数字以及下划线
 * @param rule
 * @param value
 * @param callback
 */
export function isPassword(rule, value, callback) {
  const reg =/^[_a-zA-Z0-9]+$/;
  if(value==''||value==undefined||value==null){
    return callback();
  } else {
    if (!reg.test(value)){
      return callback(new Error('仅由英文字母，数字以及下划线组成'));
    } else {
      return callback();
    }
  }
}

/**
 * 验证是否正整数
 * @param rule
 * @param value
 * @param callback
 * @returns {*}
 */
export function isInteger(rule, value, callback) {
  if (!value) {
    return callback(new Error('输入不可以为空'));
  }
  setTimeout(() => {
    if (!Number(value)) {
      return callback(new Error('请输入正整数'));
    } else {
      const re = /^[0-9]*[1-9][0-9]*$/;
      const rsCheck = re.test(value);
      if (!rsCheck) {
        return callback(new Error('请输入正整数'));
      } else {
        return callback();
      }
    }
  }, 0);
}

/**
 * 两位小数验证
 * @param rule
 * @param value
 * @param callback
 */
const validateValidity = (rule, value, callback) => {
  if (!/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/.test(value)) {
    return callback(new Error('最多两位小数！！！'));
  } else {
    return callback();
  }
};
/**
 * 密码校验
 * @param rule
 * @param value
 * @param callback
 * @returns {*}
 */
export const validatePsdReg = (rule, value, callback) => {
  if (!value) {
    return callback(new Error('请输入密码'))
  }
  if (!/^(?![\d]+$)(?![a-zA-Z]+$)(?![^\da-zA-Z]+$)([^\u4e00-\u9fa5\s]){6,20}$/.test(value)) {
    return callback(new Error('请输入6-20位英文字母、数字或者符号（除空格），且字母、数字和标点符号至少包含两种'))
  } else {
    return callback()
  }
}
/**
 * 中文校验
 * @param rule
 * @param value
 * @param callback
 * @returns {*}
 */
export const validateContacts = (rule, value, callback) => {
  if (!value) {
    return callback(new Error('请输入中文'))
  }
  if (!/^[\u0391-\uFFE5A-Za-z]+$/.test(value)) {
    return callback(new Error('不可输入特殊字符'))
  } else {
    return callback()
  }
}
/**
 * 身份证校验
 * @param rule
 * @param value
 * @param callback
 * @returns {*}
 * @constructor
 */
export const ID = (rule, value, callback) => {
  if (!value) {
    return callback(new Error('身份证不能为空'))
  }
  if (! /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test(value)) {
    return callback(new Error('请输入正确的二代身份证号码'))
  } else {
    return callback()
  }
}
/**
 * 账号校验
 * @param rule
 * @param value
 * @param callback
 * @returns {*}
 */
export const validateCode = (rule, value, callback) => {
  if (!value) {
    return callback(new Error('请输入账号'))
  }
  if (!/^(?![0-9]*$)(?![a-zA-Z]*$)[a-zA-Z0-9]{6,20}$/.test(value)) {
    return callback(new Error('账号必须为6-20位字母和数字组合'))
  } else {
    return callback()
  }
}
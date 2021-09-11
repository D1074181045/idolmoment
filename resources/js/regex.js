export const email_re = new RegExp(`^[\\w-]+(\\.[\\w-]+)*@[\\w-]+(\\.[\\w-]+)*(\\.[a-zA-Z]{2,4})$`);
export const nickname_re = new RegExp(`^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9]{1,12}$`);
export const signature_re = new RegExp(`^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9 ]{0,30}$`);
export const teetee_re = new RegExp(`^[\u3100-\u312f\u4e00-\u9fa5a-zA-Z0-9 ]{0,12}$`);

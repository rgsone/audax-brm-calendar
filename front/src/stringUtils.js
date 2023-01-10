function getTag(value) {
	if (value == null) {
		return value === undefined ? '[object Undefined]' : '[object Null]';
	}
	return toString.call(value);
}

export function isString(value) {
	const type = typeof value;
	return type === 'string' || (type === 'object' && value != null && !Array.isArray(value) && getTag(value) == '[object String]');
}

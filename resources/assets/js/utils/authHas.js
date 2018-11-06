import toNumberArray from './toNumberArray'
export default function(auth, n, concat) {
  auth = toNumberArray(auth)
  n = toNumberArray(n)
  
  // do concat
  if (typeof concat === 'object' || typeof concat === 'number') {
      n = toNumberArray(n.concat(concat))
  }
  // check if some n exists in auth
  let result = false
  auth.every(e => !(result = n.indexOf(e) > -1))
  return result
}
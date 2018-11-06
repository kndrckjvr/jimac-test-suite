export default function(e) {
    if (typeof e !== 'object') {
        e = [e]
    }
    // per e, convert to number
    return e.map(f => Number(f))
}